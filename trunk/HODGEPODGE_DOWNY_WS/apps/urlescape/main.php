<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', ['decode', 'encode', '解密', '加密']]],
	'ajax' => [['format', 'int']]
]);

if(!empty($_POST))
{
	switch($params['type'])
	{
		case 'encode':
		case '加密':
			$params['response'] = urlencode($params['content']);
			break;
		case 'decode':
		case '解密':
			$params['response'] = urldecode($params['content']);
			if(!$params['response'] || $params['response'] == '')
			{
				$params['response'] = 'decode faild.';
			}
			break;
		default:
			$params['response'] = 'type error.';
			break;

	}
	if($params['ajax'])
	{
		echo json_encode($params);
		exit;
	}
}

$this->assign('data', $params);
