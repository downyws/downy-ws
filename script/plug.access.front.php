<?php
// 参数处理
if(isset($_REQUEST['plugs_access']))
{
	$P_REQUEST = json_decode($_REQUEST['plugs_access'], true);
}
else
{
	$P_REQUEST['action'] = '';
}

// API接口
if($P_REQUEST['action'] == 'api')
{
	if($P_REQUEST['method'] == 'create.passport')
	{
		$temp = '';
		foreach(['ip', 'remember', 'timestamp', 'useragent'] as $v)
		{
			$P_REQUEST[$v] = isset($P_REQUEST[$v]) ? $P_REQUEST[$v] : '';
			$temp .= $P_REQUEST[$v];
		}
		if(empty($P_REQUEST['timestamp']) || $P_REQUEST['timestamp'] < time() - 60 || $P_REQUEST['timestamp'] > time() + 60)
		{
			$result = ['code' => '403.17', 'message' => 'timestamp expired.'];
		}
		else if(isset($P_REQUEST['sign']) && $P_REQUEST['sign'] == md5(ACCESS_API_KEY . $temp))
		{
			$filecache = new Filecache();
			$filecache->set('access/' . $P_REQUEST['sign'], $P_REQUEST, 86400000);
			$result = ['code' => '200', 'passport' => $P_REQUEST['sign']];
		}
		else
		{
			$result = ['code' => '403.16', 'message' => 'sign error.'];
		}
	}
	else
	{
		$result = ['code' => '404', 'message' => 'method not found.'];
	}

	echo json_encode($result);
	exit;
}
// 一次性即时访问接口
else if($P_REQUEST['action'] == 'once')
{
	if(empty($P_REQUEST['timestamp']) || $P_REQUEST['timestamp'] < time() - 60 || $P_REQUEST['timestamp'] > time() + 60)
	{
		echo 'timestamp expired.';
		exit;
	}
	else if(!isset($P_REQUEST['sign']) || $P_REQUEST['sign'] != md5(ACCESS_API_KEY . REMOTE_IP_ADDRESS . $P_REQUEST['timestamp']))
	{
		echo 'sign error.';
		exit;
	}
}
// 访问权限检查
else if(!isset($_SESSION['PLUGS.ACCESS']))
{
	$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);
	if(isset($SITE_SETTING[APP_NAME]['ACCESS']))
	{
		$filecache = new Filecache();
		$access = $filecache->get('access/' . $SITE_SETTING[APP_NAME]['ACCESS']);
		if($access !== false && $access['ip'] == REMOTE_IP_ADDRESS && $access['useragent'] == REMOTE_HTTP_USERAGENT)
		{
			if(empty($access['remember']))
			{
				$filecache->delete('access/' . $SITE_SETTING[APP_NAME]['ACCESS']);
				unset($SITE_SETTING[APP_NAME]['ACCESS']);
				$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
				setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);
			}
			$_SESSION['PLUGS.ACCESS'] = $access;
		}
		else
		{
			unset($SITE_SETTING[APP_NAME]['ACCESS']);
			$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
			setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);
		}
	}

	if(!isset($_SESSION['PLUGS.ACCESS']))
	{
		Front::redirect('http://' . ROOT_DOMAIN . '/index.php?a=set&m=accesslogin&app_name=' . APP_NAME . '&callback=' . urlencode(REMOTE_REQUEST_URI));
	}
}
