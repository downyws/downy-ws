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
			$params['data'] = [];
			$params['data']['讯雷：'] = 'thunder://' . base64_encode('AA' . $params['content'] . 'ZZ');
			$params['data']['快车：'] = 'flashget://' . base64_encode('[FLASHGET]' . $params['content'] . '[FLASHGET]') . '&abc';
			$params['data']['旋风：'] = 'qqdl://' . base64_encode($params['content']);
			break;
		case 'decode':
		case '解密':
			$temp = explode('://', $params['content']);
			if(count($temp) == 2)
			{
				$params['data'] = [];
				switch($temp[0])
				{
					case 'thunder':
						$params['data']['内容：'] = base64_decode($temp[1]);
						$params['data']['内容：'] = substr($params['data']['内容：'], 2, -2);
						break;
					case 'flashget':
						$params['data']['内容：'] = base64_decode($temp[1]);
						$params['data']['内容：'] = substr($params['data']['内容：'], 10, -12);
						break;
					case 'qqdl':
						$params['data']['内容：'] = base64_decode($temp[1]);
						break;
					default:
						$params['error'] = 'content error.';
						break;
				}
			}
			else
			{
				$params['error'] = 'content error.';
			}
			break;
		default:
			$params['error'] = 'type error.';
			break;
	}
	if($params['ajax'])
	{
		echo json_encode($params);
		exit;
	}
}

$this->assign('data', $params);
