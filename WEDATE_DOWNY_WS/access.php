<?php
$_GETA = empty($_GET['a']) ? '' : $_GET['a'];
$_GETM = empty($_GET['m']) ? '' : $_GET['m'];
$_GETT = empty($_GET['t']) ? '' : $_GET['t'];

if($_GETA == 'access' && $_GETM == 'set' && $_GETT == 'api')
{
	if(abs(time() - $_REQUEST['timestamp']) > 15)
	{
		$result = array('error' => array('code' => '', 'msg' => 'timestamp expire.'));
	}
	else if($_REQUEST['sign'] != md5(base64_encode(ACCESS_API_KEY . $_REQUEST['password'] . $_REQUEST['expire'] . $_REQUEST['ip'] . $_REQUEST['timestamp'] . $_REQUEST['useragent'])))
	{
		$result = array('error' => array('code' => '', 'msg' => 'sign error.'));
	}
	else
	{
		$result = array();
		$filecache = new Filecache();
		$key = 'access/' . md5(base64_encode($_REQUEST['password'] . $_REQUEST['ip'] . $_REQUEST['useragent'])) . '.key';
		$filecache->set($key, array('password' => $_REQUEST['password'], 'ip' => $_REQUEST['ip'], 'useragent' => $_REQUEST['useragent']), intval($_REQUEST['expire']));
	}
	echo json_encode($result);
	exit;
}
else if($_GETA != 'cookie' || $_GETM != 'set')
{
	$need_password = true;

	if(isset($_SESSION['ACCESS']))
	{
		$need_password = false;
	}
	else if(isset($_COOKIE['ACCESS']))
	{
		$filecache = new Filecache();
		$key = 'access/' . md5(base64_encode($_COOKIE['ACCESS'] . REMOTE_IP_ADDRESS . REMOTE_HTTP_USERAGENT)) . '.key';
		$access = $filecache->get($key);
		if($access && $access['password'] == $_COOKIE['ACCESS'] && $access['ip'] == REMOTE_IP_ADDRESS && $access['useragent'] == REMOTE_HTTP_USERAGENT)
		{
			$_SESSION['ACCESS'] = true;
			$need_password = false;
		}
		else
		{
			setcookie('ACCESS', '', 1, '', APP_DOMAIN);
		}
	}

	if($need_password)
	{
		$mobi_site = (isset($_COOKIE['SITE_TYPE']) && $_COOKIE['SITE_TYPE'] == 'MOBI') || (stripos($_SERVER["SCRIPT_NAME"], '/mobi/') === 0);
		Front::redirect('http://' . ROOT_DOMAIN . ($mobi_site === false ? '' : '/mobi') . '/index.php?a=access&m=set&app_name=' . APP_NAME . '&app_url=' . urlencode(APP_URL) . '&callback=' . urlencode(REMOTE_REQUEST_URI));
		exit;
	}
}
