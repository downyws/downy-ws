<?php

define('APP_NAME', 'Downy Set');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

$GLOBALS['CONFIG']['TRY_SAFE'] = array(
	'ACCESS_PASSWORD' => array(
		'KEY' => array('REMOTE_IP_ADDRESS'),
		'EXPIRE' => 600,
		'MAX_TRY' => 10,
		'PUNISH' => 900
	)
);

$GLOBALS['CONFIG']['CURL'] = array
(
	'TIMEOUT' => 60,
	'ENCODING' => 'gzip, deflate',
	'PROXY' => false, 
	'PROXYPORT' => '',
	'COOKIE' => array('OPEN' => false, 'LOCK' => false, 'PATH' => ''),
	'REFERER' => array('OPEN' => false, 'LOCK' => false, 'VALUE' => ''),
	'USERAGENT' => array('OPEN' => false, 'VALUE' => '0'),
	'AUTO_REDIRECT_COUNT' => 5
);
