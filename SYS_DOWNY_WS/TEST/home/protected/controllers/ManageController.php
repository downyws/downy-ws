<?php
/**
 * 管理功能
 */
class ManageController extends Controller 
{
	use ConsoleTrait;
	use ManageTrait;

    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
		return [
			['allow', 'actions' => ['document'], 'roles' => ['document']],
			['allow', 'actions' => ['user', 'userView', 'userRows', 'userDelete', 'userResetPwd'], 'roles' => ['user']],
			['allow', 'actions' => ['system'], 'roles' => ['system']],
			['deny']
		];
	}

	/**
	 * 内容管理
	 */
	public function actionDocument()
	{
		$this->render('document');
	}

	/**
	 * 系统设置
	 */
	public function actionSystem()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$errors = [];
			$configs = [];
			foreach($_POST as $k => $v)
			{
				$config = Config::model()->findByAttributes(['key' => $k]);
				if($config)
				{
					if(trim($v) == '')
					{
						if(!isset($errors[$k]))
						{
							$errors[$k] = [];
						}
						$errors[$k][] = '请填写值';
					}
					else
					{
						$config['value'] = trim($v);
						$configs[] = $config;
					}
				}
			}
			if($errors)
			{
				$this->renderJson(['success' => false, 'errors' => $errors]);
			}

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				foreach($configs as $v)
				{
					if(!$v->save())
					{
						throw new Exception('保存失败');
					}
				}
				$transaction->commit();
				$this->renderJson(['success' => true]);
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
			}
		}

		$config = Config::model()->findAll();
		$this->render('system', [
			'title' => '系统设置',
			'config' => $config
		]);
	}

	/**
	 * 用户管理
	 */
	public function actionUser()
	{
		$filters = $this->fetchFilters();

		$criteria = new CDbCriteria;
		$criteria->order = 'create_time DESC';

		$criteria->addCondition('is_delete = 0');

		if($filters['keywords'] !== '')
		{
			$criteria->addCondition(implode(' OR ', [
				' t.email LIKE :keywords ',
				' t.username LIKE :keywords ',
				' t.real_name LIKE :keywords ',
				' author.phone LIKE :keywords ',
				' author.mobile LIKE :keywords ',
				' author.identity LIKE :keywords ',
				' author.organization LIKE :keywords '
			]));
			$criteria->params[':keywords'] = '%' . $filters['keywords'] . '%';
		}

		$datas = User::model()->with('author')->findAll($criteria);

		$this->listUser($datas, $filters, '用户管理');
	}
	public function actionUserRows()
	{
		$ids = $_POST['ids'];
		$criteria = new CDbCriteria;
		$criteria->addCondition('id IN (' . $ids . ')');
		
		$datas = User::model()->with('author')->findAll($criteria);

		$this->listUser($datas, null, null, 'ajax');
	}
	public function actionUserView()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$id = $_POST['id'];
			unset($_POST['id']);

			if($id)
			{
				$user = User::model()->findByPk($id);
				$author = Author::model()->findByPk($id);

				$user['real_name'] = $_POST['real_name'];
			}
			else
			{
				$user = new User;
				$author = new Author;
				
				$user->attributes = $_POST;
				$user['visit_time'] = time();
			}
			$author->attributes = $_POST;
			$author['user_id'] = 0;
			if(isset($_POST['region_district']) && !empty($_POST['region_district']))
			{
				$author['region_id'] = $_POST['region_district'];
			}
			else if(isset($_POST['region_city']) && !empty($_POST['region_city']))
			{
				$author['region_id'] = $_POST['region_city'];
			}
			else if(isset($_POST['region_state']) && !empty($_POST['region_state']))
			{
				$author['region_id'] = $_POST['region_state'];
			}

			if(!$user->validate())
			{
				$this->renderJson(['success' => false, 'errors' => $user->errors]);
			}
			if(!$author->validate())
			{
				$this->renderJson(['success' => false, 'errors' => $author->errors]);
			}

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if(!$user->save())
				{
					$transaction->rollback();
					$this->renderJson(['success' => false, 'errors' => $user->errors]);
				}
				$author['user_id'] = $user['id'];
				if(!$author->save())
				{
					$transaction->rollback();
					$this->renderJson(['success' => false, 'errors' => $author->errors]);
				}

				// auth_assignment
				AuthAssignment::model()->deleteAllByAttributes(['userid' => $user['id']]);
				if(empty($_POST['role']))
				{
					throw new Exception('用户至少有一个权限');
				}
				foreach($_POST['role'] as $v)
				{
					$authAssignment = new AuthAssignment;
					$authAssignment->attributes = ['itemname' => $v, 'userid' => $user['id']];
					if(!$authAssignment->save())
					{
						$transaction->rollback();
						$this->renderJson(['success' => false, 'message' => '权限保存失败']);
					}
				}
				

				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
			}

			$this->renderJson(['success' => true, 'message' => '保存成功']);
		}

		$id = $_REQUEST['id'];
		$user = User::model()->findByPk($id);
		if(!$user)
		{
			$user = ['id' => 0];
		}
		$roles = AuthItem::model()->findAllByAttributes(['type' => 2]);

		$this->layout = '';
		$this->render('user_view', [
			'gender_list' => Yii::app()->params['gender'],
			'degree_list' => Yii::app()->params['degree'],
			'language_list' => Yii::app()->params['language'],
			'data' => $user,
			'roles' => $roles
		]);
	}
	public function actionUserDelete()
	{
		$ids = explode(',', $_POST['ids']);
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			foreach($ids as $v)
			{
				if($v != intval($v))
				{
					throw new Exception('含有错误的用户');
				}
				else if($v == Yii::app()->user->id)
				{
					throw new Exception('不能删除自己');
				}
				$user = User::model()->findByPk($v);
				$user['is_delete'] = 1;
				if(!$user->save())
				{
					throw new Exception('删除用户失败');
				}
			}
			$transaction->commit();
			$this->renderJson(['success' => true, 'message' => '删除用户成功']);
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
		}
	}
	public function actionUserResetPwd($id)
	{
		$user = User::model()->findByPk($id);
		if($user)
		{
			$use_email = false;
			if($pwd = $user->resetPassword($use_email))
			{
				$this->renderJson(['success' => true, 'message' => $use_email ? '重置密码成功，重置链接已发送至邮箱' : ('重置密码成功，新密码：' . $pwd)]);
			}
		}
		$this->renderJson(['success' => false, 'message' => '重置密码失败']);
	}
}