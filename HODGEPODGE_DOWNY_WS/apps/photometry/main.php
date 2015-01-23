<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', [
		'µlx', 'mlx', 'lx', 'klx', 'lm/m2',
		'lm/cm2', 'fc', 'ph', 'nox', 'cd/m2',
		'kcd/m2', 'cd/cm2', 'cd/ft2', 'fL', 'L',
		'nit', 'stilb', 'T', 'lm.s', 'lm.min',
		'lm.h', 'lm', 'cd.sr', 'lx.m2', 'cd',
		'lm/sr', 'HK', 'candlepower'
	]]]
]);

$this->assign('data', $params);




