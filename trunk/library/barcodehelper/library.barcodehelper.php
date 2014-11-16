<?php
define('PLUGINS_DIR', dirname(__FILE__) . '/plugins/');

class BarcodeHelper
{
	private $_type = null;			// 条码类型
	private $_result = null;		// 输出格式 ['obj', '路径', 'draw']
	private $_code = null;			// 条码内容
	private $_text = null;			// 显示文字 xxxxxxx
	private $_color = null;			// 前景色 #000000
	private $_bgcolor = null;		// 背景色 #000000
	private $_unit_width = null;	// 粗细 1-10
	private $_width = null;
	private $_height = null;		// 高度 > 0
	private $_margin = null;		// 边距 [0,0,0,0] >= 0
	private $_font = null;			// 文字 ['top||bottom' => 0, 'align' => 'left||right||center', 'size' => 0, 'path' => 'path']
	private $_ext = null;			// 扩展

	private $_digit = null;

	public function __construct($type, $result, $code, $text, $color, $bgcolor, $unit_width, $height, $margin, $font, $ext)
	{
		$this->_type = $type;
		$this->_result = $result;
		$this->_code = is_string($code) ? $code : '';
		$this->_text = is_string($text) ? $text : '';
		$preg_color = '/^\#[0-9A-F]{6}$/i';
		$this->_color = preg_match($preg_color, $color) ? $color : '#000000';
		$this->_bgcolor = preg_match($preg_color, $bgcolor) ? $bgcolor : '#FFFFFF';
		$this->_unit_width = is_int($unit_width) ? min(max($unit_width, 1), 10) : 2;
		$this->_height = is_int($height) ? max($height, 1) : 30;
		$this->_margin = [2, 2, 2, 2];
		if(is_array($margin) && count($margin) == 4)
		{
			foreach($margin as $k => $v)
			{
				$this->_margin[$k] = is_int($v) ? max($v, 0) : 2;
			}
		}
		if(is_array($font))
		{
			$this->_font = $font;
		}
		else if($this->_text != '')
		{
			$this->_font = [];
		}
		if($this->_font != null)
		{
			if(isset($this->_font['top']))
			{
				$this->_font['top'] = (!is_int($this->_font['top']) || $this->_font['top'] < 0) 
					? 4 : $this->_font['top'];
			}
			else if(isset($this->_font['bottom']))
			{
				$this->_font['bottom'] = (!is_int($this->_font['bottom']) || $this->_font['bottom'] < 0) 
					? 4 : $this->_font['bottom'];
			}
			else
			{
				$this->_font['bottom'] = 4;
			}
			$this->_font['align'] = (!isset($this->_font['align']) || !in_array($this->_font['align'], ['left', 'center', 'right'])) 
				? 'center' : $this->_font['align'];
			$this->_font['size'] = !isset($this->_font['size']) || !is_int($this->_font['size']) ? 12 : $this->_font['size'];
		}
		$this->_ext = $ext;
	}

	public function draw()
	{
		// 生成条码数据
		$digit = $this->getDigit();
		// 生成图像
		$img = ImageCreate($this->getWidth(), $this->_height);
		$this->_color = ImageColorAllocate($img, 
			hexdec(substr($this->_color, 1, 2)), 
			hexdec(substr($this->_color, 3, 2)), 
			hexdec(substr($this->_color, 5, 2))
		);
		$this->_bgcolor = ImageColorAllocate($img, 
			hexdec(substr($this->_bgcolor, 1, 2)), 
			hexdec(substr($this->_bgcolor, 3, 2)), 
			hexdec(substr($this->_bgcolor, 5, 2))
		);
		imagefill($img, 0, 0, $this->_bgcolor);
		$height_barcode = $this->_height - $this->_margin[0] - $this->_margin[2];
		$ys = $this->_margin[0];
		if($this->_font != null)
		{
			$xy4 = imagettfbbox($this->_font['size'], 0, $this->_font['path'] , $this->_text);
			$xyw = abs($xy4[2] - $xy4[0]);
			$xyh = abs($xy4[1] - $xy4[7]);
			$height_barcode -= $xyh;

			if(isset($this->_font['top']))
			{
				$height_barcode -= $this->_font['top'];
				$ys += $xyh + $this->_font['top'];
			}
			if(isset($this->_font['bottom']))
			{
				$height_barcode -= $this->_font['bottom'];
			}
		}
		$ye = $ys + $height_barcode - 1;
		$xs = $this->_margin[3];
		for($i = 0; $i < strlen($digit); $i++)
		{
			$xe = $xs + ($this->_unit_width - 1);
			if($digit[$i])
			{
				imagefilledrectangle($img, $xs, $ys, $xe, $ye, $this->_color);
			}
			$xs = $xe + 1;
		}

		// 文字 font text
		if($this->_text != '' && $this->_font)
		{
			if(isset($this->_font['top']))
			{
				$y = $this->_margin[0] + $xyh;
			}
			else
			{
				$y = $this->_height - $this->_margin[2];
			}

			if($this->_font['align'] == 'left')
			{
				$x = $this->_margin[3];
			}
			else if($this->_font['align'] == 'right')
			{
				$x = $this->_width - $this->_margin[1] - $xyw;
			}
			else
			{
				$x = $this->_margin[3] + ($this->_width - $this->_margin[1] - $this->_margin[3]) / 2 - $xyw / 2;
			}

			ImageTtfText($img, $this->_font['size'], 0, $x, $y, $this->_color, $this->_font['path'], $this->_text);
		}

		// 返回图片对象
		if($this->_result == 'obj')
		{
			return $img;
		}
		
		// 输出图片流
		if($this->_result == 'draw')
		{
			header("Content-type: image/png");
			imagepng($img);
			imagedestroy($img);
			exit;
		}

		// 输出文件
		$folder = dirname($this->_result);
		if(!is_dir($this->_result) && file_exists($folder) && is_dir($folder))
		{
			return imagepng($img, $this->_result);
		}

		// 都不符合的返回图片对象
		return $img;
	}

	public function getDigit()
	{
		if($this->_digit == null)
		{
			switch($this->_type)
			{
				case 'code128':
					include_once(PLUGINS_DIR . 'class.code128.php');
					$this->_digit = Code128::getDigit($this->_code, $this->_ext);
					break;
				case 'code39':
					include_once(PLUGINS_DIR . 'class.code39.php');
					$this->_digit = Code39::getDigit($this->_code, $this->_ext);
					break;
				default:
					throw new Exception('Unknow type.');
			}
		}
		return $this->_digit;
	}

	public function getWidth()
	{
		if($this->_width == null)
		{
			$digit = $this->getDigit();
			$this->_width = strlen($digit) * $this->_unit_width + $this->_margin[1] + $this->_margin[3];
		}
		return $this->_width;
	}
}
