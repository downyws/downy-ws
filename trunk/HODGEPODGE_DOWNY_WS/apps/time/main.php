<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', [
		'time', 'frequency'
	]]],
	'type' => [['valid', 'in', '', '', [
		'year', 'month', 'week', 'day', 'hour',
		'minute', 's', 'ms', 'µs', 'ns', 
		'nHz', 'µHz', 'mHz', 'Hz', 'kHz',
		'MHz', 'GHz', 'THz', 'cps', 'rpm',
		'BPM', 'rad/s', 'rad/min', 'rad/h', 'rad/day',
		'degrees/s', 'degrees/min', 'degrees/h', 'degrees/day'
	]]]
]);

$this->assign('data', $params);
