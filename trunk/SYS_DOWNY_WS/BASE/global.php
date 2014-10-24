<?php

define('APP_NAME', 'Downy System Manager');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['SITES'] = [
	'Downy Accounting Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px 0px;'
	],
	'Downy Set Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -32px;'
	],
	'Downy Hodgepodge Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -64px;'
	],
	'Downy Resources Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -96px;'
	],
	'Downy Share Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -128px;'
	],
	'Downy WeDate Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -160px;'
	],
	'Downy Wedding Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -192px;'
	],
	'Downy Weixin Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -224px;'
	],
	'Downy WWW Manager' => [
		'LOGO' => 'background-image:url(/images/siteicons.png);background-position:0px -256px;'
	]
];

$GLOBALS['CONFIG']['MESSAGE'] = [
		'UNKNOW_CODE' => ['TYPE' => 'error', 'TITLE' => '未知错误代码', 'DETAIL' => function($params){
			return 'Code: ' . $params['code'];
		}],
		'APP_UNEXISTS' => ['TYPE' => 'error', 'TITLE' => '应用不存在', 'DETAIL' => function($params){
			return 'App Name: ' . $params['data'];
		}],
		'UNSUPPORT_FRAMESET' => ['TYPE' => 'error', 'TITLE' => '浏览器不支持<b>Frameset</b>框架', 'DETAIL' => 
			'请修改浏览器设置或更换其他浏览器，建议使用Firefox。'
		]
];

$GLOBALS['CONFIG']['ACCESS_SET'] = [
	'TRY_SAFE' => [
		'EXPIRE' => 3600,
		'MAX_TRY' => 3,
		'PUNISH' => 3600
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
