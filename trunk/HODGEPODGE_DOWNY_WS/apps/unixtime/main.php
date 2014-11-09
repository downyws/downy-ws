<?php
$params = $this->_submit->obtain($_REQUEST, [
	'todate' => [['format', 'trim']],
	'tostamp' => [['format', 'trim']]
]);

if($params['todate'])
{
	$params = $this->_submit->obtain($_REQUEST, [
		'timestamp' => [['format', 'trim']]
	]);
	$params['type'] = 'todate';
}
else if($params['tostamp'])
{
	$params = $this->_submit->obtain($_REQUEST, [
		'dy' => [['format', 'trim']],
		'dm' => [['format', 'trim']],
		'dd' => [['format', 'trim']],
		'dh' => [['format', 'trim']],
		'di' => [['format', 'trim']],
		'ds' => [['format', 'trim']]
	]);
	$params = [
		'date' => $params['dy'] . '-' . $params['dm'] . '-' . $params['dd'] . ' ' . $params['dh'] . ':' . $params['di'] . ':' . $params['ds'],
		'type' => 'tostamp'
	];
}
$this->assign('data', $params);
