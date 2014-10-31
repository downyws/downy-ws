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
		if(empty($P_REQUEST['timestamp']) || $P_REQUEST['timestamp'] < time() - 30 || $P_REQUEST['timestamp'] > time() + 30)
		{
			$result = ['code' => '403.17', 'message' => 'timestamp expired.'];
		}
		else if(isset($P_REQUEST['sign']) && $P_REQUEST['sign'] == md5(ACCESS_API_KEY . $temp))
		{
			$filecache = new Filecache();
			$filecache->set('access/' . $P_REQUEST['sign'], $P_REQUEST, 100);
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
	if(empty($P_REQUEST['timestamp']) || $P_REQUEST['timestamp'] < time() - 30 || $P_REQUEST['timestamp'] > time() + 30)
	{
		echo 'timestamp expired.';
		exit;
	}
	else if(!isset($P_REQUEST['sign']) || $P_REQUEST['sign'] != md5(ACCESS_API_KEY . $P_REQUEST['random'] . REMOTE_IP_ADDRESS . $P_REQUEST['timestamp']))
	{
		echo 'sign error.';
		exit;
	}
	else
	{
		$filecache = new Filecache();
		$random = $filecache->get('access/once/' . $P_REQUEST['sign']);
		if($random !== false)
		{
			echo 'sign of the random is used.';
			exit;
		}
		$filecache->set('access/once/' . $P_REQUEST['sign'], true, 600);
	}
}
// 访问权限检查
else if(!isset($_SESSION['PLUGS.ACCESS']))
{
	if(isset($_COOKIE['SYS_MANAGER_ACCESS']))
	{
		$filecache = new Filecache();
		$access = $filecache->get('access/' . $_COOKIE['SYS_MANAGER_ACCESS']);
		if($access !== false && $access['ip'] == REMOTE_IP_ADDRESS && $access['useragent'] == REMOTE_HTTP_USERAGENT)
		{
			$filecache->delete('access/' . $_COOKIE['SYS_MANAGER_ACCESS']);
			setcookie('SYS_MANAGER_ACCESS', '', time() - 1, '/', SYS_ROOT_DOMAIN);

			$_SESSION['PLUGS.ACCESS'] = $access;
		}
	}

	if(!isset($_SESSION['PLUGS.ACCESS']))
	{
		Front::redirect('http://' . SYS_ROOT_DOMAIN . '/index.php?a=set&m=accesslogin&app_name=' . APP_NAME . '&callback=' . urlencode(REMOTE_REQUEST_URI));
	}
}
