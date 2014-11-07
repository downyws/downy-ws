<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'chr' => [['valid', 'in', '', 'l', ['l', 'u']]],
	'ajax' => [['format', 'int']]
]);

if(!empty($_POST))
{
	$params['response'] = md5($params['content']);
	if($params['chr'] == 'u')
	{
		$params['response'] = strtoupper($params['response']);
	}

	if($params['ajax'])
	{
		echo json_encode($params);
		exit;
	}
}

$this->assign('data', $params);
