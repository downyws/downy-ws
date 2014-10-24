<?php

define('APP_NAME', 'Downy Weixin Manager');

define('APP_TIMEZONE', 'Asia/Shanghai');

// 根系统域名
$_t_srd = explode('.', $_SERVER['HTTP_HOST']);
define('SYS_ROOT_DOMAIN',  $_t_srd[count($_t_srd) - 3] . '.' . $_t_srd[count($_t_srd) - 2] . '.' . $_t_srd[count($_t_srd) - 1]);
define('SYS_ROOT_URL', 'http://' . SYS_ROOT_DOMAIN . '/');

session_start();

define('APP_PAGER_SIZE', 15);

// Simsimi
$GLOBALS['CONFIG']['SIMSIMI'] = [
	'API' => 'http://api.simsimi.com/request.p?lc=ch&ft=0.0&key=' . SIMSIMI_KEY . '&text=',
	// http://sandbox.api.simsimi.com/request.p?lc=ch&ft=0.0&key=your_paid_key&text=内容
	// http://api.simsimi.com/request.p?lc=ch&ft=0.0&key=your_paid_key&text=内容
	'CURL' => [
		'TIMEOUT' => 3,
		'ENCODING' => 'gzip, deflate',
		'PROXY' => false, 
		'PROXYPORT' => '',
		'COOKIE' => ['OPEN' => false, 'LOCK' => false, 'PATH' => ''],
		'REFERER' => ['OPEN' => false, 'LOCK' => false, 'VALUE' => ''],
		'USERAGENT' => ['OPEN' => false, 'VALUE' => '0'],
		'AUTO_REDIRECT_COUNT' => 1
	]
];
