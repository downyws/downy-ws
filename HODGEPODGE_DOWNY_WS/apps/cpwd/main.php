<?php
$data = ['len' => ''];

$params = $this->_submit->obtain($_REQUEST, [
	'len' => [['format', 'int']]
]);
if($params['len'] > 0)
{
	$data['len'] = $params['len'];
}

$params = $this->_submit->obtainArray($_REQUEST, [
	'cb' => [['valid', 'int', '', '', null]]
]);
foreach($params as $v)
{
	$data['cb_' . $v['cb']] = true;
}

$this->assign('data', $data);
