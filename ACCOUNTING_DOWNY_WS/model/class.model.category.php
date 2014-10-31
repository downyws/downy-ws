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
		$key = 'category.temp';
		$result = $filecache->get($key);

		if($no_cache || !$result)
		{
			$result = [];
			$temp = $this->getObjects(null);
			foreach($temp as $v)
			{
				$result[$v['id']] = $v;
			}
			$filecache->set($key, $result, 300);
		}

		return $result;
	}

	public function getAllForSelect($no_cache = false)
	{
		$filecache = new Filecache();
		$key = 'category_forsel.temp';
		$result = $filecache->get($key);

		if($no_cache || !$result)
		{
			$temp = $this->getObjects(null);
			$result = [];
			foreach($temp as $v)
			{
				if($v['parent_id'] == 0)
				{
					$result[$v['id']] = ['id' => $v['id'], 'title' => $v['title'], 'child' => [], 'sort_array' => $v['sort']];
				}
			}
			foreach($temp as $v)
			{
				if($v['parent_id'] > 0)
				{
					$result[$v['parent_id']]['child'][] = ['id' => $v['id'], 'title' => $v['title'], 'sort_array' => $v['sort']];
				}
			}
			$result = $this->sortArray($result, 'utl');
			foreach($result as $k => $v)
			{
				$result[$k]['child'] = $this->sortArray($result[$k]['child'], 'utl');
			}

			$filecache->set($key, $result, 300);
		}

		return $result;
	}

	private function sortArray($arr, $type = '')
	{
		$result = [];

		if(!in_array($type, ['utl', 'ltu']))
		{
			$type = 'utl';
		}

		$temp = [];
		foreach($arr as $k => $v)
		{
			$s = $v['sort_array'];
			$i = isset($v['id']) && is_numeric($v['id']) ? $v['id'] : 0;
			unset($v['sort_array']);
			$temp[] = ['s' => $s, 'i' => $i, 'key' => $k, 'val' => $v];
		}
		$c = count($temp);
		for($i = 0; $i < $c; $i++)
		{
			for($j = $i; $j < $c; $j++)
			{
				if(
					($type == 'utl' && ($temp[$i]['s'] < $temp[$j]['s'] || ($temp[$i]['s'] == $temp[$j]['s'] && $temp[$i]['i'] < $temp[$j]['i']))) ||
					($type == 'ltu' && ($temp[$i]['s'] > $temp[$j]['s'] || ($temp[$i]['s'] == $temp[$j]['s'] && $temp[$i]['i'] > $temp[$j]['i'])))
				)
				{
					$t = $temp[$i];
					$temp[$i] = $temp[$j];
					$temp[$j] = $t;
				}
			}
		}

		for($i = 0; $i < $c; $i++)
		{
			$result[] = $temp[$i]['val'];
		}

		return $result;
	}
}
