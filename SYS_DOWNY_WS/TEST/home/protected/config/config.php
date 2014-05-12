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
];