<?php
class ModelTrySafe
{
	protected $_configs = null;
	protected $_filecache = null;

	public function __construct($configs = null)
	{
		if($configs !== null)
		{
			$this->_configs = $configs;
		}
		else
		{
			$this->_configs = $GLOBALS['CONFIG']['TRY_SAFE'];
		}
		foreach($this->_configs as $k => $v)
		{
			if(is_array($v['KEY']))
			{
				$key = '';
				foreach($v['KEY'] as $_v)
				{
					$key .= constant($_v);
				}
				$this->_configs[$k]['KEY'] = $key;
			}
		}
		$this->_filecache = new Filecache();
	}

	public function setConfigs($key, $config)
	{
		if($key === null)
		{
			$this->_configs = $config;
		}
		else
		{
			$this->_configs[$key] = $config;
		}
		foreach($this->_configs as $k => $v)
		{
			if(is_array($v['KEY']))
			{
				$key = '';
				foreach($v['KEY'] as $_v)
				{
					$key .= constant($_v);
				}
				$this->_configs[$k]['KEY'] = $key;
			}
		}
	}

	public function isMax($key)
	{
		$config = $this->_configs[$key];
		$count = $this->get($key);
		return $count >= $config['MAX_TRY'];
	}

	public function get($key)
	{
		$config = $this->_configs[$key];
		$data_path = 'trysafe/' . $key . '/' . $config['KEY'] . '.data';
		$data = $this->_filecache->get($data_path);

		if($data === false)
		{
			return 0;
		}

		$data = json_decode($data, true);

		if(isset($data['punish']))
		{
			if($data['punish'] > time())
			{
				return $config['MAX_TRY'];
			}
			unset($data['punish']);
		}

		$count = 0;
		foreach($data['list'] as $v)
		{
			if($v + $config['EXPIRE'] > time())
			{
				$count++;
			}
		}
		return $count;
	}

	public function goUp($key)
	{
		$config = $this->_configs[$key];
		$data_path = 'trysafe/' . $key . '/' . $config['KEY'] . '.data';
		$data = $this->_filecache->get($data_path);

		if($data === false)
		{
			$data = array('list' => array());
		}
		else
		{
			$data = json_decode($data, true);
		}

		$data['list'][] = time();
		$expire = $this->_getExpire($key, $data);
		$this->_filecache->set($data_path, json_encode($data), $expire);
	}

	public function clear($key)
	{
		$config = $this->_configs[$key];
		$data_path = 'trysafe/' . $key . '/' . $config['KEY'] . '.data';
		$this->_filecache->delete($data_path);
	}

	public function punish($key, $overwrite = false)
	{
		$config = $this->_configs[$key];
		$data_path = 'trysafe/' . $key . '/' . $config['KEY'] . '.data';
		$data = $this->_filecache->get($data_path);

		if($data === false)
		{
			$data = array('list' => array());
		}
		else
		{
			$data = json_decode($data, true);
		}
		
		if(!isset($data['punish']) || $data['punish'] <= time() || $overwrite)
		{
			$data['punish'] = time() + $config['PUNISH'];
			$expire = $this->_getExpire($key, $data);
			$this->_filecache->set($data_path, json_encode($data), $expire);
		}
	}

	public function punishCountdown($key)
	{
		$config = $this->_configs[$key];
		$data_path = 'trysafe/' . $key . '/' . $config['KEY'] . '.data';
		$data = $this->_filecache->get($data_path);

		if($data === false)
		{
			return 0;
		}

		$data = json_decode($data, true);

		if(isset($data['punish']) && $data['punish'] > time())
		{
			return $data['punish'] - time();
		}

		return 0;
	}

	protected function _getExpire($key, $data)
	{
		$config = $this->_configs[$key];
		$expire = isset($data['punish']) ? ($data['punish'] - time()) : 0;

		foreach($data['list'] as $v)
		{
			if($v + $config['EXPIRE'] - time() > $expire)
			{
				$expire = $v + $config['EXPIRE'] - time();
			}
		}

		return $expire;
	}
}
