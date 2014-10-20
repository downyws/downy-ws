<?php
class ModelAccess
{
	protected $_configs = null;
	protected $_filecache = null;
	protected $_cache_prefix = 'access/try_safe/';

	public function __construct($configs)
	{
		$this->_configs = $configs;
		$this->_filecache = new Filecache();
	}

	public function trySafeLog($key)
	{
		$data = $this->_filecache->get($this->_cache_prefix . $key);
		if($data === false)
		{
			$data = ['list' => []];
		}

		$time = time();

		$data['list'][] = $time;
		$expire = $time + $this->_configs['TRY_SAFE']['EXPIRE'];

		$count = $this->trySafeCount($key);
		if($count + 1 >= $this->_configs['TRY_SAFE']['MAX_TRY'])
		{
			$expire = $data['punish'] = $time  + $this->_configs['TRY_SAFE']['PUNISH'];
		}

		$this->_filecache->set($this->_cache_prefix . $key, $data, $expire);
	}

	public function trySafeCount($key)
	{
		$data = $this->_filecache->get($this->_cache_prefix . $key);
		if($data === false)
		{
			return 0;
		}

		if(isset($data['punish']) && $data['punish'] > time())
		{
			return $this->_configs['TRY_SAFE']['MAX_TRY'];
		}

		$count = 0;
		foreach($data['list'] as $v)
		{
			if($v + $this->_configs['TRY_SAFE']['EXPIRE'] > time())
			{
				$count++;
			}
		}
		return $count;
	}

	public function trySafeLaveCount($key)
	{
		return $this->_configs['TRY_SAFE']['MAX_TRY'] - $this->trySafeCount($key);
	}

	public function trySafePunish($key)
	{
		$data = $this->_filecache->get($this->_cache_prefix . $key);

		if($data === false)
		{
			return 0;
		}

		if(isset($data['punish']) && $data['punish'] > time())
		{
			return $data['punish'] - time();
		}

		return 0;
	}

	public function createPassport($access, $once, $password)
	{
		$url = $access['URL'];
		$post = [
			'action' => 'api',
			'method' => 'create.passport',
			'ip' => REMOTE_IP_ADDRESS,
			'remember' => $once ? 0 : 1,
			'timestamp' => time(),
			'useragent' => REMOTE_HTTP_USERAGENT
		];
		$post['sign'] = md5(
			$access['KEY'] .
			$post['ip'] .
			$post['remember'] .
			$post['timestamp'] .
			$post['useragent']
		);

		if(isset($access['EXT']))
		{
			$access['EXT']($url, $post, $password);
		}

		Factory::loadLibrary('curlhelper');
		$curlhelper = new CurlHelper($this->_configs['CURL']);
		$response = $curlhelper->request($url, ['plugs_access' => json_encode($post)]);
		if($response === false)
		{
			return false;
		}
		$response = json_decode($response['body'], true);
		if(!$response || $response['code'] != '200')
		{
			return false;
		}

		return $response['passport'];
	}
}
