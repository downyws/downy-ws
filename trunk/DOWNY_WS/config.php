<?php

// 调试模式
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 资源站点
define('RESOURCES_DOMAIN', 'http://resources.mydowny.ws/');

// 站点访问密码
$GLOBALS['CONFIG']['ACCESS'] = [
	'' => [
		'NAME' => '',
		'LOGO' => '',
		'URL' => '',
		'KEY' => '',
		'PASSWORDS' => ['']
	]
];
