<?php

return array_merge_recursive([
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'=>'上海经济研究投审稿系统',

	'preload'=>['log'],

	'import'=>[
		'application.models.*',
		'application.components.*',
	],

	'modules'=>[
		'gii'=>[
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			'ipFilters'=>['127.0.0.1', '192.168.147.*'],
		],
	],

	'components' => [
		'user' => [],
		'urlManager' => [
			'urlFormat' => 'path',
			'rules' => [
				'/' => 'index/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			],
		],
		'cache' => [
			'class' => 'CFileCache'
		],
		/*'errorHandler'=>[
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		],*/
		'log'=>[
			'class'=>'CLogRouter',
			'routes'=>[
				[
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				],
				// uncomment the following to show log messages on web pages
				/*
				[
					'class'=>'CWebLogRoute',
				],
				*/
			],
		],
	],

	'params' => [
		'degree' => ['1' => '学士', '2' => '硕士', '3' => '博士']
	],
], require dirname(__FILE__) . '/config.php');