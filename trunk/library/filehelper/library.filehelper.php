<?php
class FileHelper
{
	public function getDirSize($path)
	{
		$handle = opendir($path);
		$size = 0;
		while(($file = readdir($handle)) !== false)
		{
			if($file != "." && $file != "..")
			{
				$size += is_dir("$path/$file") ? $this->getDirSize("$path/$file") : filesize("$path/$file");
			}
		}
		closedir($handle);
		return $size;
	}

	public function getEncode($file)
	{
		// mb_convert_encoding
		$string = file_get_contents($file);

		if($string === @iconv('UTF-8', 'ASCII', @iconv('ASCII', 'UTF-8', $string)))
		{
			return 'ASCII';
		}
		else if($string === @iconv('UTF-8', 'GB2312', @iconv('GB2312', 'UTF-8', $string)))
		{
			return 'GB2312';
		}
		else if(chr(239) . chr(187) . chr(191) == substr($string, 0, 3))
		{
			return 'UTF-8 BOM';
		}
		else if($string === @iconv('UTF-8', 'UTF-8', @iconv('UTF-8', 'UTF-8', $string)))
		{
			return 'UTF-8';
		}
		return 'UNKNOW';
	}

	public function getExtension($filename)
	{
		$handle = fopen($filename, "rb");
		$info = fread($handle, 2);
		fclose($handle);
		$info = @unpack("C2chars", $info);
		$info = intval($info['chars1'] . $info['chars2']);

		switch ($info)
		{ 
			case 6677: $file_type = 'bmp'; break;
			case 7173: $file_type = 'gif'; break;
			case 7784: $file_type = 'midi'; break;
			case 7790: $file_type = 'exe'; break;
			case 8075: $file_type = 'zip'; break;
			case 8297: $file_type = 'rar'; break;
			case 13780: $file_type = 'png'; break;
			case 255216: $file_type = 'jpg'; break;
			default: 
				$file_type = pathinfo($path, PATHINFO_EXTENSION);
				$file_type = empty($file_type) ? false : $file_type;
				break;
		}

		return $file_type;
	}

	public function fileCreate($file_target)
	{
		if(is_dir($file_target))
		{
			return true;
		}
		else if(file_exists($file_target))
		{
			$file_target = dirname($file_target);
		}
		return mkdir($file_target, 0777, true);
	}

	public function fileDel($file_target)
	{
		if(!file_exists($file_target))
		{
			return true;
		}
		$dir = opendir($file_target);
		while($file = readdir($dir))
		{
			if($file != '.' && $file != '..')
			{
				if(is_dir($file_target . '/' . $file))
				{
					$this->fileDel($file_target . '/' . $file);
				}
				else
				{
					unlink($file_target . '/' . $file);
				}
			}
		}
		closedir($dir);
		return rmdir($file_target);
	}

	public function fileCopy($file_from, $file_to, $overwrite = false)
	{
		if(!file_exists($file_to) || !is_dir($file_to))
		{
			if(!$this->fileCreate($file_to))
			{
				return false;
			}
		}
		$dir = opendir($file_from);
		while($file = readdir($dir))
		{
			if ($file != '.' && $file != '..')
			{
				if(is_dir($file_from . '/' . $file))
				{
					if(!$this->fileCopy($file_from . '/' . $file, $file_to . '/' . $file, $overwrite))
					{
						closedir($dir);
						return false;
					}
				}
				else if(!file_exists($file_to . '/' . $file) || $overwrite)
				{
					if(!copy($file_from . '/' . $file, $file_to . '/' . $file))
					{
						closedir($dir);
						return false;
					}
				}
			}
		}
		closedir($dir);
		return true;
	}

	public function fileMove($file_from, $file_to, $overwrite = false)
	{
		if(!file_exists($file_to) || !is_dir($file_to))
		{
			if(!$this->fileCreate($file_to))
			{
				return false;
			}
		}
		$dir = opendir($file_from);
		while($file = readdir($dir))
		{
			if ($file != '.' && $file != '..')
			{
				if(is_dir($file_from . '/' . $file))
				{
					if(!$this->fileMove($file_from . '/' . $file, $file_to . '/' . $file, $overwrite))
					{
						closedir($dir);
						return false;
					}
				}
				else if(!file_exists($file_to . '/' . $file) || $overwrite)
				{
					if(!rename($file_from . '/' . $file, $file_to . '/' . $file))
					{
						closedir($dir);
						return false;
					}
				}
				else if(file_exists($file_from . '/' . $file))
				{
					unlink($file_from . '/' . $file);
				}
			}
		}
		closedir($dir);
		rmdir($file_from);
		return true;
	}
}
