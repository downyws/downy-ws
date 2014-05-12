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
		$this->layout = 'front';
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
					$this->renderJson(['success' => true, 'url' => 'audit/index']);
				}

				$this->renderJson(['success' => true, 'url' => 'article/index']);
			}
			else
			{
				$this->renderJson(['success' => false, 'message' => '用户名不存在或密码错误']);
			}
		}

		if(($referer = Yii::app()->request->urlReferrer) and !preg_match('/' . preg_quote(Yii::app()->request->hostInfo, '/') . self::$skipRedirect . '/', $referer))
		{
			$_SESSION['login_redirect'] = $referer;
		}

		$this->redirect('/');
	}

	public function actionRegister()
	{
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			// 成功后跳转
			$this->redirect('/');
		}

		
		$this->render('register', ['degree_list' => Yii::app()->params['degree']]);
	}

	public function actionFieldExists($key, $val)
	{
		if(in_array($key, ['username', 'email']))
		{
			$obj = User::model()->findByAttributes([$key => $val]);
			$this->renderJson(['success' => !$obj]);
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