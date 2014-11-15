<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'row' => [['valid', 'int', '', '', ''], ['valid', 'between', '', '', [2, 999]]],
	'type' => [['valid', 'in', '', '', ['decode', 'encode', '解密', '加密']]],
	'ajax' => [['format', 'int']]
]);

if(!empty($_POST))
{
	Factory::loadLibrary('cipherhelper');
	$cipherhelper = new CipherHelper();
	if($params['row'] < 2 && $params['ajax'])
	{
			$params['response'] = 'row error.';
	}
	else if($params['row'] >= 2)
	{
		switch($params['type'])
		{
			case 'encode':
			case '加密':
				$params['response'] = $cipherhelper->railfence_encode($params['row'], $params['content']);
				break;
			case 'decode':
			case '解密':
				$params['response'] = $cipherhelper->railfence_decode($params['row'], $params['content']);
				if(!$params['response'] || $params['response'] == '')
				{
					$params['response'] = 'decode faild.';
				}
				break;
			default:
				$params['response'] = 'type error.';
				break;

		}
	}
	if($params['ajax'])
	{
		echo json_encode($params);
		exit;
	}
}

$this->assign('data', $params);
