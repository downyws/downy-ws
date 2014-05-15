<?php

class UserController extends Controller 
{
	use ConsoleTrait;

    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
		return [
			['deny', 'actions' => ['profile'], 'roles' => ['audit']],
			['deny', 'actions' => ['profile', 'password'], 'users' => ['?']],
		];
	}

	public function actions()
	{
		return array(
			'captcha' => [
				'class'=> 'system.web.widgets.captcha.CCaptchaAction',
				'backColor' => 0xF1F2F6,
			]
		);
	}

	public function beforeAction($action)
	{
		if(in_array($action->getId(), ['password', 'profile']))
		{
			$this->layout = 'console';
		}
		else
		{
			$this->layout = 'front';
		}
		return parent::beforeAction($action);
	}

	public function actionLogin()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			// 验证码
			$captcha = Yii::createComponent(['class' => 'Captcha', 'attributes' => $_POST]);
			if(!$captcha->validate())
			{
				$this->renderJson(['success' => false, 'message' => implode("\n", array_values($captcha->errors['captcha']))]);
			}

			// 用户名
			if(empty($_POST['username']) or !($username = trim($_POST['username'])))
			{
				$this->renderJson(['success' => false, 'message' => '请填写用户名']);
			}

			// 密码
			if(empty($_POST['password']) or !($password = trim($_POST['password'])))
			{
				$this->renderJson(['success' => false, 'message' => '请填写密码']);
			}

			$identity = new UserIdentity($username, $password);
			if($identity->authenticate())
			{
				$user = Yii::app()->user;
				$user->login($identity);
				if($user->checkAccess('audit'))
				{
					$this->renderJson(['success' => true, 'url' => '/audit/index']);
				}

				$this->renderJson(['success' => true, 'url' => '/article/index']);
			}
			else
			{
				$this->renderJson(['success' => false, 'message' => '用户名不存在或密码错误']);
			}
		}
	}

	public function actionRegister()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$user = new User;
				$user->attributes = $_POST;
				$user['visit_time'] = time();
				if(!$user->validate())
				{
					$this->renderJson(['success' => false, 'errors' => $user->errors]);
				}

				$author = new Author;
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
				if(!$author->validate())
				{
					$this->renderJson(['success' => false, 'errors' => $author->errors]);
				}

				// 验证码
				$captcha = Yii::createComponent(['class' => 'Captcha', 'attributes' => $_POST]);
				if(!$captcha->validate())
				{
					$this->renderJson(['success' => false, 'errors' => $captcha->errors]);
				}

				$user['password'] = md5($user['password']);
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

				$transaction->commit();

				// 成功后自动登录跳转
				$identity = new UserIdentity($_POST['username'], $_POST['password']);
				$identity->authenticate();
				$user = Yii::app()->user;
				$user->login($identity);
				if($user->checkAccess('audit'))
				{
					$this->renderJson(['success' => true, 'url' => '/audit/index']);
				}
				$this->renderJson(['success' => true, 'url' => '/article/index']);
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
			}
		}

		$this->render('register', [
			'gender_list' => Yii::app()->params['gender'],
			'degree_list' => Yii::app()->params['degree'],
			'language_list' => Yii::app()->params['language'],
			'request' => $_REQUEST
		]);
	}

	public function actionFieldExists($key, $val)
	{
		if(in_array($key, ['username', 'email']))
		{
			$obj = User::model()->findByAttributes([$key => $val]);
			$this->renderJson(['success' => !$obj, 'message' => '已经存在']);
		}
		throw new CHttpException(404, 'The requested page does not exist.');
	}

	public function actionRecover()
	{
		// 检查重置链接
		if(isset($_REQUEST['code']))
		{
			$validate = Validate::model()->findByAttributes(['code' => $_REQUEST['code'], 'user_id' => base64_decode($_REQUEST['key'])]);

			if(!$validate || $validate['create_time'] + 7200 < time() || $validate['visit_time'] > 0)
			{
				$this->render('recover', ['error' => true]);
			}
			else if('POST' == $_SERVER['REQUEST_METHOD'])
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$validate['visit_time'] = time();
					if(!$validate->save())
					{
						throw new Exception('修改密码失败');
					}
					$user = User::model()->findByPk($validate['user_id']);
					$user['password'] = md5($_POST['password']);
					if(!$user->save())
					{
						throw new Exception('修改密码失败');
					}
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollback();
					$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
				}

				// 成功后自动登录跳转
				$identity = new UserIdentity($user['username'], $_POST['password']);
				$identity->authenticate();
				$user = Yii::app()->user;
				$user->login($identity);
				if($user->checkAccess('audit'))
				{
					$this->renderJson(['success' => true, 'url' => '/audit/index']);
				}
				$this->renderJson(['success' => true, 'url' => '/article/index']);
			}
			else
			{
				$this->render('recover', ['code' => $_REQUEST['code'], 'key' => $_REQUEST['key']]);
			}
		}
		// 提交重置密码信息
		else if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			// 验证码
			$captcha = Yii::createComponent(['class' => 'Captcha', 'attributes' => $_POST]);
			if(!$captcha->validate())
			{
				$this->renderJson(['success' => false, 'errors' => $captcha->errors]);
			}

			// 邮箱
			$user = User::model()->findByAttributes(['email' => $_POST['email'], 'is_delete' => 0]);
			if(!$user)
			{
				$this->renderJson(['success' => false, 'errors' => ['email' => '邮箱不存在']]);
			}

			// 攻击检查
			$config = Yii::app()->params['recoverSafe'];
			$key = 'user/recover/' . $user['email'];
			$data = Yii::app()->cache->get($key);
			if($data !== false)
			{
				$this->renderJson(['success' => false, 'interval' => $data - time()]);
			}
			Yii::app()->cache->set($key, time() + $config['interval'], $config['interval']);
			$key = 'user/recover/' . ip2long($_SERVER['REMOTE_ADDR']);
			$data = Yii::app()->cache->get($key);
			if($data === false)
			{
				$data = [];
			}
			$count = 0;
			foreach($data as $k => $v)
			{
				if($v - time() > 300)
				{
					unset($data[$k]);
				}
				else
				{
					$count++;
				}
			}
			if($count > $config['atk']['count'])
			{
				$this->renderJson(['success' => false, 'message' => '您的IP重置密码过于频繁，请稍后尝试']);
			}
			$data[] = time();
			Yii::app()->cache->set($key, $data, $config['atk']['time']);

			// 重置
			if(!$user->resetPassword(true))
			{
				$this->renderJson(['success' => false, 'message' => '密码重置邮件发送失败']);
			}
			$this->renderJson(['success' => true]);
		}
		else
		{
			$this->render('recover');
		}
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->request->baseUrl . '/');
	}

	/**
	 * 修改资料
	 */
	public function actionProfile()
	{
		$user = User::model()->findByPk(Yii::app()->user->id);
		$author = Author::model()->findByPk(Yii::app()->user->id);

		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			unset($_POST['id']);
			unset($_POST['user_id']);

			$user->attributes = $_POST;
			$author->attributes = $_POST;

			if(!$user->validate())
			{
				$this->renderJson(['success' => false, 'errors' => $user->errors]);
			}
			if(!$author->validate())
			{
				$this->renderJson(['success' => false, 'errors' => $author->errors]);
			}
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

			$transaction = Yii::app()->db->beginTransaction();

			if(!$user->save())
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'errors' => $user->errors]);
			}
			if(!$author->save())
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'errors' => $author->errors]);
			}

			$transaction->commit();
			$this->renderJson(['success' => true, 'message' => '修改资料成功']);
		}

		$this->render('profile', [
			'title' => '修改资料', 
			'data' => ['user' => $user, 'author' => $author],
			'gender_list' => Yii::app()->params['gender'],
			'degree_list' => Yii::app()->params['degree'],
			'language_list' => Yii::app()->params['language']
		]);
	}

	/**
	 * 修改密码
	 */
	public function actionPassword()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$user = User::model()->findByPk(Yii::app()->user->id);
			if(md5($_POST['old_password']) != $user['password'])
			{
				$this->renderJson(['success' => false, 'errors' => ['old_password' => '原密码错误']]);
			}
			$user['password'] = md5($_POST['password']);
			if(!$user->save())
			{
				$this->renderJson(['success' => false, 'message' => '修改密码失败']);
			}
			$this->renderJson(['success' => true, 'message' => '修改密码成功']);
		}

		$this->render('password', ['title' => '修改密码']);
	}
}