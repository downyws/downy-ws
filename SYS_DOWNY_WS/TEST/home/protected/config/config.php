<?php

return [
	'components' => [
		'db'=>[
			'connectionString' => 'mysql:host=127.0.0.1;dbname=sherj',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'tablePrefix' => 'contrib_'
		],
	],
	'params' => [
		'siteUrl' => 'http://www.mysh-erj.org',
		'email' => [
			'SMTPAuth' => true, 
			'Port' => 25, 
			'Host' => 'smtp.qq.com', 
			'Username' => '747877297@qq.com', 
			'Password' => 'myj123456', 
			'From' => '747877297@qq.com', 
			'FromName' => '上海经济研究投审稿系统', 
			'IsHTML' => true
		]
	]
];