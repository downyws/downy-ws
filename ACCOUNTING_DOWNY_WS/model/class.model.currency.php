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
}
