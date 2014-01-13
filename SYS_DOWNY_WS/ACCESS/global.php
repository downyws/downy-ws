<?php

define('APP_NAME', 'Downy Access Manager');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['TRY_SAFE'] = array(
	'ACCESS' => array(
		'KEY' => array('REMOTE_IP_ADDRESS'),
		'EXPIRE' => 3600,
		'MAX_TRY' => 3,
		'PUNISH' => 3600
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
