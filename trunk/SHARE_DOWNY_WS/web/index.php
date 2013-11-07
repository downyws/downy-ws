<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');
define('APP_DIR_TOSITE', APP_DIR . 'tosite/');

include_once(APP_DIR . 'config.php');
include_once(APP_DIR . 'global.php');

include_once('../../framework/framework.core.php');

if(!empty($_GET['s']) && $_GET['s'] != 'PC')
{
	if(!isset($_COOKIE['SITE_TYPE']))
	{
		if(in_array(REMOTE_DEVICE_TYPE, array('PAD', 'PHONE')))
		{
			Front::redirect('http://' . ROOT_DOMAIN . '/mobi/index.php?a=set&m=sitetype');
		}
	}
	else if($_COOKIE['SITE_TYPE'] == 'MOBI')
	{
		Front::redirect('mobi');
	}
}

Front::dispatch();
