<?php
$params = $this->_submit->obtain($_REQUEST, [
	'cont_from' => [['format', 'trim']],
	'dict_from' => [['format', 'trim']],
	'dict_to' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', ['decode', 'encode', 'dictionary', '解密', '加密']]],
	'ajax' => [['format', 'int']]
]);

if(!empty($_POST))
{
	Factory::loadLibrary('cipherhelper');
	$cipherhelper = new CipherHelper();
	$params['cont_from'] = str_replace(["\r", "\n"], '', $params['cont_from']);
	if($params['ajax'] && $params['cont_from'] == '')
	{
		$params['message'] = '内容不能为空';
	}
	else if($params['ajax'] && $params['cont_from'] != '' && $params['type'] != 'dictionary')
	{
		switch($params['type'])
		{
			case 'encode':
			case '加密':
				$dict = createDict($params['cont_from'], $params['dict_from'], $params['dict_to']);
				break;
			case 'decode':
			case '解密':
				$dict = createDict($params['cont_from'], $params['dict_to'], $params['dict_from']);
				break;
		}
		if($dict === false)
		{
			$params['message'] = '字典格式错误或缺少数据';
		}
		else
		{
			switch($params['type'])
			{
				case 'encode':
				case '加密':
					$params['cont_to'] = $cipherhelper->simplesubstitution_encode($dict, $params['cont_from']);
					break;
				case 'decode':
				case '解密':
					$dict = array_flip($dict);
					$params['cont_to'] = $cipherhelper->simplesubstitution_decode($dict, $params['cont_from']);
					break;
			}
		}
	}
	else if($params['cont_from'] != '')
	{
		$dict = null;
		$params['cont_to'] = $cipherhelper->simplesubstitution_encode($dict, $params['cont_from']);
		if($params['cont_to'] !== false)
		{
			$params['dict_from'] = implode('', array_keys($dict));
			$params['dict_to'] = implode('', array_values($dict));
		}
		else
		{
			$params['message'] = '加密失败，请检查字典和加密内容是否符合要求';
		}
	}
}

if($params['ajax'])
{
	echo json_encode($params);
	exit;
}
else
{
	$this->assign('data', $params);
}



function createDict($content, $keys, $vals)
{
	if(mb_strlen($keys) != mb_strlen($vals))
	{
		return false;
	}

	$temp = [];
	$c = mb_strlen($keys);
	for($i = 0; $i < $c; $i++)
	{
		$temp[] = mb_substr($keys, $i, 1);
	}
	$keys = $temp;

	$temp = [];
	$c = mb_strlen($vals);
	for($i = 0; $i < $c; $i++)
	{
		$temp[] = mb_substr($vals, $i, 1);
	}
	$vals = $temp;

	$temp = [];
	$c = mb_strlen($content);
	for($i = 0; $i < $c; $i++)
	{
		$temp[] = mb_substr($content, $i, 1);
	}
	$temp = array_unique($temp);
	foreach($temp as $v)
	{
		if(!in_array($v, $keys))
		{
			return false;
		}
	}

	$result = [];
	$c = count($keys);
	for($i = 0; $i < $c; $i++)
	{
		$result[$keys[$i]] = $vals[$i];
	}

	return $result;
}
