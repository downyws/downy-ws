<?php
class CipherHelper
{
	public function keyvalv1_encode($key, $val)
	{
		for($j = 0; $j < 2; $j++)
		{
			$key = md5($key);
			$key = 
				strrev(substr($key, 28, 4) . substr($key, 4, 4)) .
				strrev(substr($key, 20, 8)) .
				strrev(substr($key, 8, 4) . substr($key, 0, 4)) .
				strrev(substr($key, 12, 8));

			$val = base64_encode($val);
			$val = substr(md5($val), 0, 16) . $val . substr(md5($val), 16, 16);

			for($i = 0; $i < 32; $i++)
			{
				$offset = ord($key[$i]) - 38;
				if($offset >= 59)
				{
					$offset = $offset - 39;
				}

				$s1 = substr($val, 0, $offset);
				$s23 = substr($val, $offset);
				$len = intval(strlen($s23) / 2);
				$s2 = substr($s23, 0, $len);
				$s3 = substr($s23, $len);

				$val = $s3 . strrev($s2) . $s1;
			}
		}

		return $val;
	}
	public function keyvalv1_decode($key, $val)
	{
		$temp = [];
		for($j = 0; $j < 2; $j++)
		{
			$key = md5($key);
			$key = 
				strrev(substr($key, 28, 4) . substr($key, 4, 4)) .
				strrev(substr($key, 20, 8)) .
				strrev(substr($key, 8, 4) . substr($key, 0, 4)) .
				strrev(substr($key, 12, 8));
			$temp[] = $key;
		}
		$key = array_reverse($temp);

		for($j = 0; $j < 2; $j++)
		{
			for($i = 31; $i >= 0; $i--)
			{
				$offset = ord($key[$j][$i]) - 38;
				if($offset >= 59)
				{
					$offset = $offset - 39;
				}

				$s1 = substr($val, -1 * $offset);
				$s32 = substr($val, 0, -1 * $offset);
				$len = intval(strlen($s32) / 2) + (strlen($s32) % 2);
				$s3 = substr($s32, 0, $len);
				$s2 = substr($s32, $len);

				$val = $s1 . strrev($s2) . $s3;
			}

			$c = substr($val, 0, 16) . substr($val, -16);
			$val = substr($val, 16,-16);
			if(md5($val) != $c)
			{
				return false;
			}
			$val = base64_decode($val);
		}

		return $val;
	}
}
