<?php
$data = [];

$params = $this->_submit->obtainArray($_REQUEST, [
	'item' => [['format', 'trim', '', '', null]]
]);

foreach($params as $v)
{
	if($v['item'] != '')
	{
		$data[] = $v['item'];
	}
}

$this->assign('data', $data);
