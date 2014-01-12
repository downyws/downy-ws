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
$GLOBALS['CONFIG']['DB'] = array
(
	'HOST' => 'localhost',
	'PORT' => '3306',
	'USERNAME' => 'root',
	'PASSWORD' => 'root',
	'DBNAME' => 'downy_ws',
	'CHARSET' => 'utf8',
	'PREFIX' => 'weixin_',
	'QUERY_LIMIT_BYTE' => '200000'
);
