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
		var_dump('debug');
		//if(!$result)
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
						$app['thumb'] = file_get_contents($path['thumb']);

						$result[] = $app;
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

		$this->assign('apps', $result);
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

		include_once($path['main']);

		$config = include_once($path['config']);
		$config['view'] = $path['view'];
		$this->assign('config', $config);
	}
}
