<?php
class ActionSet extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodAccessLogin()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'app_name' => [['format', 'trim']],
			'callback' => [['valid', 'url', '', '', null]],
			'password' => [['format', 'trim']]
		]);
		$params['message'] = '';

		$SITES = $GLOBALS['CONFIG']['ACCESS'];
		if(!isset($SITES[$params['app_name']]))
		{
			$this->redirect('/index.php?a=index&m=message&code=APP_UNEXISTS&data=' . $params['app_name']);
		}

		if(empty($params['callback']))
		{
			$params['callback'] = $SITES[$params['app_name']]['URL'];
		}

		$accessObj = Factory::getModel('access', $GLOBALS['CONFIG']['ACCESS_SET']);
		$punish = $accessObj->trySafePunish(md5(REMOTE_IP_ADDRESS));

		if($punish > 0)
		{
			$params['message'] = '失败次数过多，请' . ceil($punish / 60) . '分钟后再试。';
		}
		else
		{
			$passport = null;

			// 表单提交
			if(!empty($_POST))
			{
				if(in_array($params['password'], $SITES[$params['app_name']]['PASSWORDS']))
				{
					$passport = $accessObj->createPassport($SITES[$params['app_name']], $params['password']);
				}
				else
				{
					$accessObj->trySafeLog(md5(REMOTE_IP_ADDRESS));
					$params['message'] = '密码错误。';
				}
			}

			if($passport !== null)
			{
				if(!is_string($passport) || strlen($passport) != 32)
				{
					$params['message'] = '创建Passport失败。';
				}
				else
				{
					$_COOKIE['SYS_MANAGER_ACCESS'] = $passport;
					setcookie('SYS_MANAGER_ACCESS', $_COOKIE['SYS_MANAGER_ACCESS'], time() + 30, '/', APP_DOMAIN);
					$this->redirect($params['callback']);
				}
			}
		}

		$params['logo'] = $SITES[$params['app_name']]['LOGO'];

		$this->assign('site', $SITES[$params['app_name']]);
		$this->assign('params', $params);
	}
}
