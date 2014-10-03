<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');

include_once(APP_DIR . 'config.php');
include_once(APP_DIR . 'global.php');

include_once('../../framework/framework.core.php');

if(empty($_GET['s']) || $_GET['s'] != 'PC')
{
	if(!isset($_COOKIE['SITE_TYPE']) || $_COOKIE['SITE_TYPE'] != 'PC')
	{
		if(in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']))
		{
			Front::redirect('http://' . ROOT_DOMAIN . '/mobi/index.php?a=set&m=mobiwarning&app_name=' . urlencode(APP_NAME) . '&app_url=' . urlencode(APP_URL) . '&callback=' . urlencode(REMOTE_REQUEST_URI));
		}
	}
}

include_once(APP_DIR . 'access.php');
Front::dispatch();
