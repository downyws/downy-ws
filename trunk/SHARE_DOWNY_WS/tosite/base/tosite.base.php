<?php
class ToSiteBase
{
	public $sites = array();

	public function __construct($site = '')
	{
		if(file_exists(APP_DIR_TOSITE . $site . '/install'))
		{
			unlink(APP_DIR_TOSITE . $site . '/install');
			$this->install($site);
		}
		else if(!file_exists(APP_DIR_TOSITE . 'base/sites.php'))
		{
			$this->install('');
		}
		else
		{
			$this->sites = file_get_contents(APP_DIR_TOSITE . 'base/sites.php');
			$this->sites = json_decode($this->sites, true);
		}
	}

	public function getName($key)
	{
		foreach($this->sites as $v)
		{
			if($v['key'] == $key)
			{
				return $v['name'];
			}
		}
		return null;
	}

	public function getUrl($params)
	{
		throw new Exception('class:' . __CLASS__ . ', function:' . __FUNCTION__ . ', line:' . __LINE__ . ', Subclass not exists.');
	}

	public function install($site)
	{
		if(empty($site))
		{
			$dir = opendir(APP_DIR_TOSITE);
			while($file = readdir($dir))
			{
				if($file != '.' && $file != '..')
				{
					$this->install($file);
				}
			}
		}
		else
		{
			Factory::loadLibrary('filehelper');
			$filehelper = new FileHelper();
			$filehelper->fileDel(APP_DIR . 'web/tosite/' . $site);
			$filehelper->fileCopy(APP_DIR_TOSITE . $site . '/resources', APP_DIR . 'web/tosite/' . $site);

			if($site != 'base')
			{
				$handle = fopen(APP_DIR_TOSITE . $site . '/tosite.' . $site . '.php', 'r');
				$config = array();
				while($line = fgets($handle, 1024))
				{
					if(strripos($line, '#- ') === 0)
					{
						$config[] = trim(str_replace('#- ', '', $line));
						if(count($config) == 2)
						{
							break;
						}
					}
				}
				$site = array('key' => $site, 'name' => $config[0], 'sort' => floatval($config[1]));
				if(empty($this->sites))
				{
					$this->sites[] = $site;
				}
				else
				{
					$temp = $this->sites;
					$this->sites = array();
					foreach($temp as $k => $v)
					{
						if(!empty($site) && $v['sort'] < $site['sort'])
						{
							$this->sites[] = $site;
							unset($site);
						}
						$this->sites[] = $v;
					}
					if(!empty($site))
					{
						$this->sites[] = $site;
					}
				}
				file_put_contents(APP_DIR_TOSITE . 'base/sites.php', json_encode($this->sites));
			}
		}
	}
}
