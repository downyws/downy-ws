<?php
// 显示二维码
$params = $this->_submit->obtain($_REQUEST, [
	'imgkey' => [['format', 'trim']],
	'download' => [['format', 'int']],
]);
if($params['imgkey'])
{
	$path = APP_DIR_CACHE . 'filecache/app/' . $key . '/' . $params['imgkey'] . '.png';
	if(!file_exists($path))
	{
		Front::redirect(null, 404);
	}
	else if($params['download'])
	{
		$fp = fopen($path, 'r');
		$file_size = filesize($path);

		header('Content-type: text/html;charset=utf-8');
		header('Content-type: application/octet-stream');
		header('Accept-Ranges: bytes');
		header('Accept-Length: ' . $file_size);
		header('Content-Disposition: attachment; filename=' . $params['imgkey'] . '.png');

		$buffer = 1024;
		$file_count = 0;
		while(!feof($fp) && $file_count < $file_size)
		{
			$file_con = fread($fp, $buffer);
			$file_count += $buffer;
			echo $file_con;
		}
		fclose($fp);
	}
	else
	{
		$size = getimagesize($path);
		$fp = fopen($path, "rb");
		header('Content-type: ' . $size['mime']);
		fpassthru($fp);
	}

	exit;
}

// 表单处理
if(!empty($_POST))
{
	$params = $this->_submit->obtain($_REQUEST, [
		'content' => [['format', 'trim']],
		'level' => [['valid', 'empty', '', 3, null], ['valid', 'in', '', 3, [0, 1, 2, 3]]],
		'ajax' => [['format', 'int']],
		'download' => [['format', 'trim']]
	]);
	if($params['ajax'] || $params['download'] != '')
	{
		$is_ajax = $params['ajax'] ? true : false;

		// 检查必要参数
		$error = [];
		if($params['content'] == '')
		{
			$error[] = 'content can not empty';
		}
		$content = $params['content'];
		$level = $params['level'];

		// 创建二维码
		if(empty($error))
		{
			$params = $this->_submit->obtain($_REQUEST, [
				'size' => [['valid', 'empty', '', 3, null], ['valid', 'between', '', 3, [1, 10]], ['format', 'int']],
				'margin' => [['valid', 'empty', '', 4, null], ['valid', 'between', '', 4, [0, 10]], ['format', 'int']],
				'color' => [['valid', 'regex', '', '#000000', '/^#([0-9A-F]{6})$/']],
				'bgcolor' => [['valid', 'regex', '', '#FFFFFF', '/^#([0-9A-F]{6})$/']]
			]);

			$filename = md5(json_encode([$content, $level, $params]));
			$temp = new Filecache();
			$temp->set('app/' . $key . '/' . $filename . '.png', 0, 0);

			Factory::loadLibrary('qrcodehelper');
			QRcode::png($content, APP_DIR_CACHE . 'filecache/app/' . $key . '/' . $filename . '.png', $level, $params['size'], $params['margin'], false, $params['color'], $params['bgcolor']);
			$success = $filename;
		}

		// 输出
		if(!empty($error))
		{
			echo $is_ajax ? json_encode(['message' => $error]) : implode('<br />', $error);
		}
		else if(isset($success))
		{
			$url = '/index.php?a=index&m=app&name=' . $key . '&imgkey=' . $success;
			if($is_ajax)
			{
				echo json_encode(['img' => $url]);
			}
			else
			{
				
				Front::redirect($url . '&download=1');
			}
		}
		exit;
	}
}

$this->assign('data', $params);
