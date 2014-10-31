<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');

include_once('../../config/config.accounting.php');
include_once(APP_DIR . 'global.php');

include_once('../../framework/framework.core.php');

$must = isset($_GET['must']) ? intval($_GET['must']) : 0;

$time = time();
$random = mt_rand(100000, 999999);
$plugs_access = json_encode([
	'action' => 'once',
	'random' => $random,
	'timestamp' => $time,
	'sign' => md5(ACCESS_API_KEY . $random . REMOTE_IP_ADDRESS . $time)
]);

$url = APP_URL . 'index.php?a=cron&m=exchangerate&must=' . $must . '&plugs_access=' . urlencode($plugs_access);
header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $url);
