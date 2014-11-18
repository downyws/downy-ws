<?php
$params = $this->_submit->obtain($_REQUEST, [
	'dy' => [['format', 'trim']],
	'dm' => [['format', 'trim']],
	'dd' => [['format', 'trim']]
]);
$params = [
	'date' => $params['dy'] . '-' . $params['dm'] . '-' . $params['dd']
];
if(strtotime($params['date']) === false)
{
	$params['date'] = '';
}else{
	$params['date'] = date('Y-m-d', strtotime($params['date']));
}
$this->assign('data', $params);
