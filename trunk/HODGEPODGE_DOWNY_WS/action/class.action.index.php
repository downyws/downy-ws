<?php
class ActionIndex extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$filecache = new Filecache();
		$key = 'apps.temp';
		$result = $filecache->get($key);

		if(!$result)
		{
			$result = [];
			$handle = opendir(APP_DIR_APPS);
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..')
				{
					$path = [
						'config' => APP_DIR_APPS . $file . '/config.php',
						'thumb' => APP_DIR_APPS . $file . '/thumb.html'
					];
					if(file_exists($path['config']) && file_exists($path['thumb']))
					{
						$app = include_once($path['config']);
						if($app['online'])
						{
							$app['key'] = $file;
							$app['thumb'] = $path['thumb'];
							$result[] = $app;
						}
					}
				}
			}

			// 排序
			for($i = 0; $i < count($result); $i++)
			{
				for($j = $i + 1; $j < count($result); $j++)
				{
					if($result[$i]['rank'] < $result[$j]['rank'])
					{
						$t = $result[$j]['rank'];
						$result[$j]['rank'] = $result[$i]['rank'];
						$result[$i]['rank'] = $t;
					}
				}
			}

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		$this->assign('color', $GLOBALS['CONFIG']['COLOR']);
		$this->assign('apps', $result);
	}

	public function methodSearch()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'name' => [['format', 'trim']]
		]);

		$filecache = new Filecache();
		$key = 'tags.temp';
		$tags = $filecache->get($key);

		if(!$tags)
		{
			$tags = [];
			$handle = opendir(APP_DIR_APPS);
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..')
				{
					$path = [
						'config' => APP_DIR_APPS . $file . '/config.php',
					];
					if(file_exists($path['config']))
					{
						$app = include_once($path['config']);
						if($app['online'])
						{
							$temp = explode(' ', $app['tag']);
							foreach($temp as $k => $v)
							{
								if($v == '')
								{
									unset($temp[$k]);
								}
							}
							$tags[$file] = $temp;
						}
					}
				}
			}

			$filecache->set($key, $tags, strtotime(date('Y-m-d')) + 86399 - time());
		}

		if($params['name'] == '')
		{
			$result = ['data' => 'all_show'];
		}
		else
		{
			$result = ['data' => []];

			foreach($tags as $key => $tag)
			{
				foreach($tag as $t)
				{
					if(stripos($t, $params['name']) !== false)
					{
						$result['data'][] = $key;
						break;
					}
				}
			}
			if(empty($result['data']))
			{
				$result = ['data' => 'not_found'];
			}
		}

		echo json_encode($result);
	}

	public function methodApp()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'name' => [['format', 'trim'], ['valid', 'empty', 'App is not exists.', null, null]]
		]);

		if(!empty($this->_submit->errors))
		{
			$this->assign('config', ['title' => 'App Error']);
			$this->assign('message', $this->_submit->errors);
			return;
		}
		
		$path = [
			'config' => APP_DIR_APPS . $params['name'] . '/config.php',
			'main' => APP_DIR_APPS . $params['name'] . '/main.php',
			'view' => APP_DIR_APPS . $params['name'] . '/view.html'
		];
		if(!file_exists($path['config']) || !file_exists($path['main']) || !file_exists($path['view']))
		{
			$this->assign('config', ['title' => 'App Error']);
			$this->assign('message', ['App missing files']);
			return;
		}

		$config = include_once($path['config']);
		if(!$config['online'])
		{
			$this->assign('config', ['title' => 'App Offline']);
			$this->assign('message', ['App is offline, Please check back soon.']);
			return;
		}

		include_once($path['main']);
		$config['view'] = $path['view'];
		$this->assign('config', $config);
	}
}
