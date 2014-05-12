<?php

return array_merge_recursive([
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'id' => 'sherj',
	'name'=>'上海经济研究投审稿系统',

	'preload'=>['log', 'session'],

	'import'=>[
		'application.models.*',
		'application.components.*',
	],

	'language' => 'zh_cn',

	'modules'=>[
		'gii'=>[
			'class'=>'system.gii.GiiModule',
			'password'=>'pwd',
			'ipFilters'=>['127.0.0.1', '192.168.147.*'],
		],
	],

	'components' => [
		'authManager' => [
			'class' => 'CDbAuthManager',
			'itemTable' => '{{auth_item}}',
			'itemChildTable' => '{{auth_item_child}}',
			'assignmentTable' => '{{auth_assignment}}',
		],
		'user' => [
			'loginUrl' => '/', 
		],
		'urlManager' => [
			'urlFormat' => 'path',
			'rules' => [
				'' => 'index/index',
				'<controller:\w+>' => '<controller>/index',
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
			],
			'baseUrl' => ''
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
					//'levels'=>'error, warning',
				],
				// uncomment the following to show log messages on web pages
				/*
				[
					'class'=>'CWebLogRoute',
				],
				*/
			],
		],
		'session' => [
			'class' => 'CHttpSession',
			'autoStart' => true,
			'sessionName' => 's',
			'cookieParams' => [
				'path' => '/',
				'httpOnly' => true,
			],
		],
	],

	'params' => [
		'defaultRole' => '投稿人',
		'degree' => ['1' => '学士', '2' => '硕士', '3' => '博士']
	],
], require dirname(__FILE__) . '/config.php');