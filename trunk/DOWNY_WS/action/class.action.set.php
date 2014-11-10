<?php
class ActionSet extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodSiteConfig()
	{
		$SITES = $GLOBALS['CONFIG']['SITES'];
		$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);

		if(!empty($_POST))
		{
			$t = [];
			foreach($_POST as $v)
			{
				$d = substr($v, 1, stripos($v, ']') - 1);
				$s = substr($v, stripos($v, ']') + 1);
				if(!isset($t[$s]))
				{
					$t[$s] = [];
				}
				switch($d)
				{
					case 'MOBI':
					case 'PC':
						$t[$s]['TYPE'] = $d;
						break;
					case 'WARNING':
						$t[$s]['CLOSE_WARNING'] = 0;
						break;
				}
			}

			foreach($SITES as $k => $v)
			{
				if(!isset($SITE_SETTING[$k]))
				{
					$SITE_SETTING[$k] = [];
				}
				if(isset($t[$k]['TYPE']))
				{
					$SITE_SETTING[$k]['TYPE'] = $t[$k]['TYPE'];
					if(isset($t[$k]['CLOSE_WARNING']))
					{
						if(!isset($SITE_SETTING[$k]['CLOSE_WARNING']) || $SITE_SETTING[$k]['CLOSE_WARNING'] > time() + 86400)
						{
							$SITE_SETTING[$k]['CLOSE_WARNING'] = 0;
						}
					}
					else
					{
						$SITE_SETTING[$k]['CLOSE_WARNING'] = time() + 86400000;
					}
				}
				else
				{
					$SITE_SETTING[$k]['TYPE'] = ($SITES[$k]['ONLY_TYPE'] == false) ? 
						(in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) ? 'MOBI' : 'PC') : 
						$SITES[$k]['ONLY_TYPE'];
					if(in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) && $SITE_SETTING[$k]['TYPE'] == 'PC')
					{
						$SITE_SETTING[$k]['CLOSE_WARNING'] = 0;
					}
				}
			}
			$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
			setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);

			$this->redirect('../');
		}
		else
		{
			foreach($SITES as $k => $v)
			{
				$SITES[$k]['TYPE'] = ($SITES[$k]['ONLY_TYPE'] == false) ? 
					(in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) ? 'MOBI' : 'PC') : 
					$SITES[$k]['ONLY_TYPE'];

				if(isset($SITE_SETTING[$k]['TYPE']))
				{
					$SITES[$k]['TYPE'] = $SITE_SETTING[$k]['TYPE'];
				}

				$SITES[$k]['WARNING'] = false;
				if($SITES[$k]['TYPE'] == 'PC')
				{
					$SITES[$k]['WARNING'] = in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) ? true : false;
					if(isset($SITE_SETTING[$k]['CLOSE_WARNING']) && is_numeric($SITE_SETTING[$k]['CLOSE_WARNING']))
					{
						$SITES[$k]['WARNING'] = ($SITE_SETTING[$k]['CLOSE_WARNING'] < time() + 86400);
					}
				}
			}
		}

		$this->assign('handheld', in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']));
		$this->assign('sites', $SITES);
	}

	public function methodAccessPassword()
	{
		$EXISTS_PWD = ' [ - - - - - -/]';
		$SITES = $GLOBALS['CONFIG']['ACCESS'];
		$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);

		if(!empty($_POST))
		{
			Factory::loadLibrary('cipherhelper');
			$cipherhelper = new CipherHelper();

			foreach($SITES as $k => $v)
			{
				if(isset($_POST['p_' . md5($k)]))
				{
					if($_POST['p_' . md5($k)] != $EXISTS_PWD)
					{
						if(!isset($SITE_SETTING[$k]))
						{
							$SITE_SETTING[$k] = [];
						}
						unset($SITE_SETTING[$k]['ACCESS'], $SITE_SETTING[$k]['PRE_ACCESS']);

						if($_POST['p_' . md5($k)] != '')
						{
							$key = md5(time() . REMOTE_IP_ADDRESS . mt_rand() . REMOTE_HTTP_USERAGENT);
							$filecache = new Filecache();
							$filecache->set
							(
								'pre_access/' . substr($key, 0, 16), 
								$cipherhelper->keyvalv1_encode(substr($key, 16), $_POST['p_' . md5($k)]), 
								time() + 86400000
							);
							$SITE_SETTING[$k]['PRE_ACCESS'] = $key;
						}
					}
				}
				else
				{
					unset($SITE_SETTING[$k]['ACCESS'], $SITE_SETTING[$k]['PRE_ACCESS']);
				}
			}

			$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
			setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);
			$this->redirect('../');
		}
		else
		{
			foreach($SITES as $k => $v)
			{
				$SITES[$k]['ACCESS'] = (isset($SITE_SETTING[$k]['ACCESS']) || isset($SITE_SETTING[$k]['PRE_ACCESS'])) ? 1 : 0;
			}
		}

		$this->assign('sites', $SITES);
		$this->assign('exists_pwd', $EXISTS_PWD);
	}

	public function methodAccessLogin()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'app_name' => [['format', 'trim']],
			'callback' => [['valid', 'url', '', '', null]],
			'remember' => [['format', 'int']],
			'password' => [['format', 'trim']]
		]);
		$params['message'] = '';

		$SITES = $GLOBALS['CONFIG']['ACCESS'];
		if(!isset($SITES[$params['app_name']]))
		{
			$prefix = stripos(get_class($this), 'ActionMobi_') === 0 ? '/mobi/' : '/';
			$this->redirect($prefix . 'message/warning_REQUEST_PARAMS_ERROR.html');
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

			// cookie密码检查
			$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);
			if(isset($SITE_SETTING[$params['app_name']]['PRE_ACCESS']))
			{
				$key = $SITE_SETTING[$params['app_name']]['PRE_ACCESS'];
				if(strlen($key) == '32')
				{
					$filecache = new Filecache();
					$val = $filecache->get('pre_access/' . substr($key, 0, 16));
					if($val !== false)
					{
						Factory::loadLibrary('cipherhelper');
						$cipherhelper = new CipherHelper();
						$pwd = $cipherhelper->keyvalv1_decode(substr($key, 16), $val);
						if($pwd !== false && in_array($pwd, $SITES[$params['app_name']]['PASSWORDS']))
						{
							$passport = $accessObj->createPassport($SITES[$params['app_name']], false, $pwd);
						}
					}
				}
				
				if($passport === null)
				{
					$accessObj->trySafeLog(md5(REMOTE_IP_ADDRESS));

					unset($SITE_SETTING[$params['app_name']]['PRE_ACCESS']);
					$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
					setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);
				}
			}

			// 表单提交
			if(!empty($_POST))
			{
				if(in_array($params['password'], $SITES[$params['app_name']]['PASSWORDS']))
				{
					$passport = $accessObj->createPassport($SITES[$params['app_name']], !$params['remember'], $params['password']);
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
					$SITE_SETTING[$params['app_name']]['ACCESS'] = $passport;
					$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
					setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);
					$this->redirect($params['callback']);
				}
			}
		}

		$params['logo'] = $SITES[$params['app_name']]['LOGO'];

		$this->assign('site', $SITES[$params['app_name']]);
		$this->assign('params', $params);
	}
}
