<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', [
		'bq', 'kbq', 'mbq', 'gbq', 'tbq',
		'ci', 'dpm', 'rd', 'sv', 'msv',
		'usv', 'rem', 'mrem', 'x', 'hpbr',
		'hpqr', 'gy', 'jkg', 'rad'
	]]],
]);

$this->assign('data', $params);
