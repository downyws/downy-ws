<?php

// 调试模式
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 资源站点
define('RESOURCES_DOMAIN', '');

// 访问密码
define('ACCESS_API_KEY', '');

// 数据库配置
$GLOBALS['CONFIG']['DB'] = array
(
	'HOST' => '',
	'PORT' => '',
	'USERNAME' => '',
	'PASSWORD' => '',
	'DBNAME' => '',
	'CHARSET' => '',
	'PREFIX' => '',
	'QUERY_LIMIT_BYTE' => ''
);
