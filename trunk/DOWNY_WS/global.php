<?php

define('APP_NAME', 'Downy Set');

define('APP_TIMEZONE', 'Asia/Shanghai');

session_start();

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

$GLOBALS['CONFIG']['SITES'] = [
	'Downy Accounting' => ['ONLY_TYPE' => 'PC'],
	'Downy Hodgepodge' => ['ONLY_TYPE' => 'PC'],
	'Downy Set' => ['ONLY_TYPE' => false],
	'Downy Share' => ['ONLY_TYPE' => false],
	'Downy Wedate' => ['ONLY_TYPE' => 'PC'],
	'Downy Wedding' => ['ONLY_TYPE' => false],
	'Downy Weixin' => ['ONLY_TYPE' => false],
	'Downy Site' => ['ONLY_TYPE' => false]
];

$GLOBALS['CONFIG']['MESSAGE'] = [
	'WARNING' => [
		'UNKNOW_CODE' => '未知错误代码',
		'REQUEST_PARAMS_ERROR' => '请求参数错误'
	]
];

$GLOBALS['CONFIG']['ACCESS_SET'] = [
	'TRY_SAFE' => [
		'EXPIRE' => 600,
		'MAX_TRY' => 5,
		'PUNISH' => 900
	],
	'CURL' => [
		'TIMEOUT' => 60,
		'ENCODING' => 'gzip, deflate',
		'PROXY' => false, 
		'PROXYPORT' => '',
		'COOKIE' => ['OPEN' => false, 'LOCK' => false, 'PATH' => ''],
		'REFERER' => ['OPEN' => false, 'LOCK' => false, 'VALUE' => ''],
		'USERAGENT' => ['OPEN' => false, 'VALUE' => '0'],
		'AUTO_REDIRECT_COUNT' => 5
	]
];
