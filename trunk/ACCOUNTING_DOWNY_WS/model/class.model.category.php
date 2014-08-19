<?php
class ModelCategory extends Model
{
	public $_table = 'category';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getAll($no_cache = false)
	{
		$filecache = new Filecache();
		$key = $this->_table . '.temp';
		$result = $filecache->get($key);

		if($no_cache || !$result)
		{
			$temp = $this->getObjects(null);
			foreach($temp as $v)
			{
				$result[$v['id']] = $v;
			}
			$filecache->set($key, $result, 300);
		}

		return $result;
	}
}
