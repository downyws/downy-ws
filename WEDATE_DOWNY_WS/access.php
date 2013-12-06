<?php

if($_GET['a'] == 'access' && $_GET['m'] == 'setpwd')
{
	if(abs(time() - $_REQUEST['timestamp']) > 15)
	{
		$result = array('error' => array('code' => '', 'msg' => 'timestamp expire.'));
	}
	else if($_REQUEST['sign'] != md5(base64_encode(ACCESS_PASSWORD . $_REQUEST['accesspassword'] . $_REQUEST['expire'] . $_REQUEST['ip'] . $_REQUEST['timestamp'] . $_REQUEST['useragent'])))
	{
		$result = array('error' => array('code' => '', 'msg' => 'sign error.'));
	}
	else
	{
		$result = array();
		$filecache = new Filecache();
		$key = 'access/' . md5(base64_encode($_REQUEST['accesspassword'] . $_REQUEST['ip'] . $_REQUEST['useragent'])) . '.key';
		$filecache->set($key, array('accesspassword' => $_REQUEST['accesspassword'], 'ip' => $_REQUEST['ip'], 'useragent' => $_REQUEST['useragent']), intval($_REQUEST['expire']));
	}
	echo json_encode($result);
	exit;
}
else if($_GET['a'] != 'cookie' || $_GET['m'] != 'set')
{
	$need_password = true;

	if(isset($_SESSION['ACCESS_PASSWORD']))
	{
		$need_password = false;
	}
	else if(isset($_COOKIE['ACCESS_PASSWORD']))
	{
		$filecache = new Filecache();
		$key = 'access/' . md5(base64_encode($_COOKIE['ACCESS_PASSWORD'] . REMOTE_IP_ADDRESS . REMOTE_HTTP_USERAGENT)) . '.key';
		$access = $filecache->get($key);
		if($access && $access['accesspassword'] == $_COOKIE['ACCESS_PASSWORD'] && $access['ip'] == REMOTE_IP_ADDRESS && $access['useragent'] == REMOTE_HTTP_USERAGENT)
		{
			$_SESSION['ACCESS_PASSWORD'] = true;
			$need_password = false;
		}
		else
		{
			setcookie('ACCESS_PASSWORD', '', 1, '', APP_DOMAIN);
		}
	}

	if($need_password)
	{
		$mobi_site = (isset($_COOKIE['SITE_TYPE']) && $_COOKIE['SITE_TYPE'] == 'MOBI') || (stripos($_SERVER["SCRIPT_NAME"], '/mobi/') === 0);
		Front::redirect('http://' . ROOT_DOMAIN . ($mobi_site === false ? '' : '/mobi') .'/index.php?a=set&m=accesspassword&app_name=' . APP_NAME . '&app_url=' . urlencode(APP_URL) . '&callback=' . urlencode(REMOTE_REQUEST_URI));
		exit;
	}
}
