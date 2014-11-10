<?php
// 显示条形码
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
		'code' => [['format', 'trim']],
		'type' => [['valid', 'in', '', '', ['code39', 'code128-a', 'code128-b', 'code128-c']]],
		'ajax' => [['format', 'int']],
		'download' => [['format', 'trim']]
	]);
	if($params['ajax'] || $params['download'] != '')
	{
		$is_ajax = $params['ajax'] ? true : false;

		// 检查必要参数
		$error = [];
		if($params['code'] == '')
		{
			$error[] = 'barcode can not empty';
		}
		if($params['type'] == '')
		{
			$error[] = 'barcode type error';
		}

		// 创建条形码
		if(empty($error))
		{
			$code = $params['code'];
			switch($params['type'])
			{
				case 'code39':
					$ext = null;
					$type = 'code39';
					break;
				case 'code128-a':
				case 'code128-b':
				case 'code128-c':
					$ext = ['type' => ord(substr($params['type'], 8, 1)) - 96];
					$type = 'code128';
					break;
			}

			$params = $this->_submit->obtain($_REQUEST, [
				'unit_width' => [['valid', 'between', '', 2, [1, 10]], ['format', 'int']],
				'text' => [['format', 'trim']],
				'font_0' => [['valid', 'in', '', 'bottom', ['top', 'bottom']]],
				'font_0_0' => [['valid', 'gte', '', 4, 1], ['format', 'int']],
				'font_1' => [['valid', 'in', '', 'center', ['left', 'center', 'right']]],
				'font_2' => [['valid', 'gte', '', 12, 1], ['format', 'int']],
				'color' => [['format', 'trim']],
				'bgcolor' => [['format', 'trim']],
				'height' => [['valid', 'gte', '', 30, 1], ['format', 'int']],
				'margin_0' => [['valid', 'empty', '', 2, null], ['valid', 'gte', '', 2, 0], ['format', 'int']],
				'margin_1' => [['valid', 'empty', '', 2, null], ['valid', 'gte', '', 2, 0], ['format', 'int']],
				'margin_2' => [['valid', 'empty', '', 2, null], ['valid', 'gte', '', 2, 0], ['format', 'int']],
				'margin_3' => [['valid', 'empty', '', 2, null], ['valid', 'gte', '', 2, 0], ['format', 'int']]
			]);
			$font = $params['text'] != '' ? [$params['font_0'] => $params['font_0_0'], 'align' => $params['font_1'], 'size' => $params['font_2']] : null;

			$filename = md5(json_encode([$code, $ext, $type, $params]));
			$temp = new Filecache();
			$temp->set('app/' . $key . '/' . $filename . '.png', 0, 0);
			
			Factory::loadLibrary('barcodehelper');
			$barcodehelper = new BarcodeHelper(
				$type, APP_DIR_CACHE . 'filecache/app/' . $key . '/' . $filename . '.png', $code, $params['text'], $params['color'], $params['bgcolor'], $params['unit_width'], 
				$params['height'], [$params['margin_0'], $params['margin_1'], $params['margin_2'], $params['margin_3']],
				$font, $ext
			);
			if($barcodehelper->draw() === true)
			{
				$success = $filename;
			}
			else
			{
				$error[] = 'create img faild';
			}
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
