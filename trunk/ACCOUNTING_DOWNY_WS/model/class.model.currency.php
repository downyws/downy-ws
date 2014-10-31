<?php
class ModelCurrency extends Model
{
	public $_table = 'currency';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getAll($no_cache = false)
	{
		$filecache = new Filecache();
		$key = 'currency.temp';
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

	public function getAllForSelect($no_cache = false)
	{
		$filecache = new Filecache();
		$key = 'currency_forsel.temp';
		$result = $filecache->get($key);

		if($no_cache || !$result)
		{
			$temp = $this->getObjects(null);
			$result = [];
			foreach($temp as $v)
			{
				$result[$v['id']] = ['id' => $v['id'], 'title' => $v['abbr'] . ' - ' . $v['title'], 'sort_array' => $v['sort']];
			}
			$result = $this->sortArray($result, 'utl');
			$filecache->set($key, $result, 300);
		}

		return $result;
	}

	public function updateExchangeRate($id, $rate)
	{
		$sql =  ' UPDATE ' . $this->_prefix . 'currency SET ' .
				'	exchange_rate = ' . $this->escape($rate[1]) . ', ' .
				'	exchange_rate_log = CONCAT("' . $this->escape($rate[0] . "\t" . $rate[1] . "\n") . '", exchange_rate_log), ' .
				'	rate_last_update_time = ' . time() .
				' WHERE id = ' . $id;
		$res = $this->query($sql);

		if($res !== false)
		{
			return !!$this->affectedRows();
		}
		return false;
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
