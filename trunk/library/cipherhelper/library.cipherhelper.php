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

	public function railfence_encode($row, $val)
	{
		$val = str_replace("\r\n", "\n", $val);
		if($row < 1)
		{
			return false;
		}

		$result = [];
		for($i = 0; $i < $row; $i++)
		{
			$result[$i] = '';
		}

		$c = mb_strlen($val);
		for($i = 0, $t = 0, $p = 1; $i < $c; $i++)
		{
			$result[$t] .= mb_substr($val, $i, 1);
			$t += $p;
			if($t >= $row || $t < 0)
			{
				$p = $p * -1;
				$t += $p + $p;
			}
		}

		return implode('', $result);
	}
	public function railfence_decode($row, $val)
	{
		$val = str_replace("\r\n", "\n", $val);
		if($row < 1)
		{
			return false;
		}

		$count_str = mb_strlen($val);
		$count_scope = $row + $row - 2;
		$count_lave = $count_str % $count_scope;
		$itemlen = ($count_str - $count_lave) / $count_scope;

		$temp = [];
		$start = 0;
		$ys = [];
		for($i = 0; $i < $row; $i++)
		{
			$ys[$i] = 0;
			$end = $itemlen;
			if($i != 0 && $i != $row - 1)
			{
				$end = $itemlen * 2;
			}
			if($count_lave > $i)
			{
				$end += 1;
				if($i != 0 && $i != $row -1 && $count_lave > ($row - 1) * 2 - $i)
				{
					$end += 1;
				}
			}
			$temp[$i] = mb_substr($val, $start, $end);
			$start += $end;
		}

		$result = '';
		$c = mb_strlen($val);
		$x = 0;
		$z = 1;
		for($i = 0; $i < $c; $i++)
		{
			$result .= mb_substr($temp[$x], $ys[$x]++, 1);
			$x = $x + $z;
			if($x >= $row || $x < 0)
			{
				$z = $z * -1;
				$x += $z * 2;
			}
		}

		return $result;
	}

	public function simplesubstitution_encode(&$dict, $content)
	{
		$content = str_replace(["\r", "\n"], '', $content);

		if($dict === null)
		{
			$dict = $this->simplesubstitution_randomDict($content);
		}
		
		if(!$this->simplesubstitution_checkDict($dict, $content))
		{
			return false;
		}

		$result = '';
		$c = mb_strlen($content);
		for($i = 0; $i < $c; $i++)
		{
			$result .= $dict[mb_substr($content, $i, 1)];
		}

		return $result;
	}
	public function simplesubstitution_decode($dict, $content)
	{
		if($dict === null)
		{
			return false;
		}
		$dict = array_flip($dict);
		return $this->simplesubstitution_encode($dict, $content);
	}
	public function simplesubstitution_checkDict($dict, $content)
	{
		$temp = [];
		$c = mb_strlen($content);
		for($i = 0; $i < $c; $i++)
		{
			$temp[] = mb_substr($content, $i, 1);
		}
		$vals = array_unique($temp);
		$dict = array_keys($dict);
		foreach($vals as $v)
		{
			if(!in_array($v, $dict))
			{
				return false;
			}
		}
		return true;
	}
	public function simplesubstitution_randomDict($content)
	{
		$content = str_replace(["\r", "\n"], '', $content);

		$temp = [];
		$c = mb_strlen($content);
		for($i = 0; $i < $c; $i++)
		{
			$temp[] = mb_substr($content, $i, 1);
		}
		$vals = array_unique($temp);
		shuffle($vals);
		
		$temp = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz`~!@#$%^&*()_+-=[]\;,./{}|:"<>?\'';
		$c = strlen($temp);
		$keys = $vals;
		for($i = 0; $i < $c; $i++)
		{
			$keys[] = $temp[$i];
		}
		$keys = array_unique($keys);
		shuffle($keys);

		$result = [];
		$c = count($vals);
		$vals = implode('', $vals);
		for($i = 0; $i < $c; $i++)
		{
			$result[mb_substr($vals, $i, 1)] = $keys[$i];
		}

		return $result;
	}
}
