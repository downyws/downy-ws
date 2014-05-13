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
		$this->render('recover');
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
		$this->render('profile');
	}

	/**
	 * 修改密码
	 */
	public function actionPassword()
	{
		$this->render('password');
	}
}