<?php
class ModelStatistics extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getMain()
	{
		$filecache = new Filecache();
		$key = 'record_statistics.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$result = [];
			$sql =  ' SELECT r.surplus FROM ' . $this->_prefix . 'record AS r ' .
					' WHERE r.surplus_currency_id = ' . DEFAULT_SURPLUS_CURRENCY;
			$list = $this->fetchCol($sql);
			$result['income'] = 0;
			$result['expenditure'] = 0;
			foreach($list as $v)
			{
				if($v > 0)
				{
					$result['income'] += floatval($v);
				}
				else
				{
					$result['expenditure'] += floatval($v);
				}
			}
			$result['income'] = sprintf('%.5f', $result['income']);
			$result['expenditure'] = sprintf('%.5f', $result['expenditure'] * -1);
			$result['state_1'] = $this->getOne([['state' => ['eq', 1]]], 'COUNT(*)', 'record');
			$result['state_2'] = $this->getOne([['state' => ['eq', 2]]], 'COUNT(*)', 'record');
			$result['remind'] = $this->getOne([['remind_time' => ['lte', time()]], ['state' => ['eq', 2]]], 'COUNT(*)', 'record');
			$result['address'] = $this->getOne(null, 'COUNT(*)', 'address');
			$result['file'] = $this->getOne(null, 'COUNT(*)', 'file');
			$result['currency'] = $this->getOne(null, 'COUNT(*)', 'currency');
			$result['category'] = $this->getOne(null, 'COUNT(*)', 'category');

			$result['chart'] = [];
			$start_time = strtotime(date('Y-m-d', time() - 86400 * 15));
			$end_time = strtotime(date('Y-m-d', time() - 86400)) + 86399;
			$sql =  ' SELECT CONCAT(FROM_UNIXTIME(td.create_time, "%m-%d"), "-", IF(td.amount >= 0, "i", "e")) AS d, SUM(td.amount * td.exchange_rate) FROM ' . $this->_prefix . 'record AS tr ' .
					' JOIN ' . $this->_prefix . 'detail AS td ON td.record_id = tr.id ' .
					' WHERE tr.surplus_currency_id = ' . DEFAULT_SURPLUS_CURRENCY .
					'		AND td.create_time BETWEEN ' . $start_time . ' AND ' . $end_time . ' ' .
					' GROUP BY d';
			$temp = $this->fetchPairs($sql);
			for($i = $start_time; $i < $end_time; $i += 86400)
			{
				$k = date('m-d', $i);
				$result['chart'][$k] = [
					'i' => isset($temp[$k . '-i']) ? $temp[$k . '-i'] : 0, 
					'e' => isset($temp[$k . '-e']) ? $temp[$k . '-e'] : 0
				];
			}

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		return $result;
	}
}
