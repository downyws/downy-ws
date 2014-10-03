<?php

class Filecache
{
	private function getPath($key, $is_set = false)
	{
		if(strpos($key, '/../') !== false)
		{
			return false;
		}

		$path = APP_DIR_CACHE . 'filecache/' . $key;

		if(is_dir($path))
		{
			return false;
		}

		if($is_set)
		{
			$dir = dirname($path);
			if(!is_dir($dir))
			{
				mkdir($dir, 0755, true);
				return is_dir($dir) ? $path : false;
			}
		}
		elseif(!file_exists($path))
		{
			return false;
		}

		return $path;
	}

	public function set($key, $value, $expire = FRAMEWORLK_FILECACHE_EXPIRE)
	{
		if(!($path = $this->getPath($key, true)))
		{
			return false;
		}

		return file_put_contents($path, json_encode(['value' => $value, 'expire' => time() + $expire]), LOCK_EX);
	}

	public function setMulti($pairs, $expire = FRAMEWORLK_FILECACHE_EXPIRE)
	{
		if(empty($pairs) || !is_array($pairs))
		{
			return true;
		}

		$res = true;
		foreach($pairs as $key => $value)
		{
			$res = $res && $this->set($key, $value, $expire);
		}

		return $res;
	}

	public function get($key)
	{
		if(!($path = $this->getPath($key)))
		{
			return false;
		}

		$content = json_decode(file_get_contents($path), true);
		return $content['expire'] > time() ? $content['value'] : false;
	}

	public function getMulti($keys)
	{
		if(empty($keys) || !is_array($keys))
		{
			return [];
		}

		$res = [];

		foreach($keys as $key)
		{
			if($value = $this->get($key))
			{
				$res[$key] = $value;
			}
		}

		return $res;
	}

	public function delete($key)
	{
		if(!($path = $this->getPath($key)))
		{
			return false;
		}

		return unlink($path);
	}

	public function deleteMulti($keys)
	{
		if(empty($keys) || !is_array($keys))
		{
			return true;
		}

		$res = true;
		foreach($keys as $key)
		{
			$res = $this->delete($key);
		}
		return $res;
	}
}