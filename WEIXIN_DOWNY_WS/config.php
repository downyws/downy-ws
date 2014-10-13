<?php

// 调试模式
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 资源站点
define('RESOURCES_DOMAIN', '');

// Weixin TOKEN
define('WEIXIN_TOKEN', '');

// Simsimi KEY
define('SIMSIMI_KEY', '');

// 数据库配置
$GLOBALS['CONFIG']['DB'] = [
	'HOST' => '',
	'PORT' => '',
	'USERNAME' => '',
	'PASSWORD' => '',
	'DBNAME' => '',
	'CHARSET' => '',
	'PREFIX' => '',
	'QUERY_LIMIT_BYTE' => ''
];
