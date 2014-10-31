<?php
class ModelStatistics extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getThumbnail()
	{
		$filecache = new Filecache();
		$key = 'statistics/thumbnail.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$result = [];
			$start_time = strtotime(date('Y-m-d', time() - 86400 * 15));
			$end_time = strtotime(date('Y-m-d', time() - 86400)) + 86399;
			$sql =  ' SELECT CONCAT(FROM_UNIXTIME(td.create_time, "%m-%d"), "-", IF(td.amount >= 0, "i", "e")) AS d, ROUND(SUM(td.amount * td.exchange_rate), 3) ' .
					' FROM ' . $this->_prefix . 'detail AS td ' .
					' WHERE td.create_time BETWEEN ' . $start_time . ' AND ' . $end_time . ' ' .
					' GROUP BY d';
			$temp = $this->fetchPairs($sql);
			for($i = $start_time; $i < $end_time; $i += 86400)
			{
				$k = date('m-d', $i);
				$result[$k] = [
					'i' => isset($temp[$k . '-i']) ? $temp[$k . '-i'] : 0, 
					'e' => isset($temp[$k . '-e']) ? $temp[$k . '-e'] : 0
				];
			}

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		return $result;
	}

	public function search($params)
	{
		$sql =  ' SELECT ' .
				'	tr.id AS rid, ' .
				'	td.id, ' .
				'	(td.amount * td.exchange_rate) AS amount, ' . 
				'	ta.title AS address_title, ' .
				'	tc.title AS category_title, ' .
				'	td.create_time, ' . 
				'	td.remark ' . 
				' FROM ' . $this->_prefix . 'detail AS td ' .
				' JOIN ' . $this->_prefix . 'record AS tr ON td.record_id = tr.id ' .
				' JOIN ' . $this->_prefix . 'address AS ta ON tr.address_id = ta.id ' .
				' JOIN ' . $this->_prefix . 'category AS tc ON tr.category_id = tc.id ' .
				' WHERE 1 = 1 ';

		if($params['address_title'] != '')
		{
			$condition = [];
			$condition[] = ['title' => ['like', $params['address_title']]];
			$params['address_title'] = $this->getCol($condition, 'id', 'address');
			if(empty($params['address_title']))
			{
				return [];
			}
			$sql .= ' AND ta.id IN (' . implode(',', $params['address_title']) . ')';
		}

		if($params['start_time'] != '')
		{
			$params['start_time'] = strtotime($params['start_time']);
			if($params['start_time'] !== false)
			{
				$sql .= ' AND td.create_time >= ' . $params['start_time'];
			}
		}

		if($params['end_time'] != '')
		{
			$params['end_time'] = strtotime($params['end_time']);
			if($params['end_time'] !== false)
			{
				$sql .= ' AND td.create_time <= ' . ($params['end_time'] + 86399);
			}
		}

		if(!empty($params['category_id']))
		{
			$sql .= ' AND tc.id IN (' . implode(',', $params['category_id']) . ')';
		}
		
		return $this->fetchRows($sql);
	}

	public function category($start_day, $end_day, $category_id = 0)
	{
		$start_day = ($start_day == 0) ? 0 : strtotime(date('Y-m-d', $start_day));
		$end_day = ($end_day == 0) ? 0 : strtotime(date('Y-m-d', $end_day)) + 86399;

		$filecache = new Filecache();
		$key = 'statistics/category/' . $start_day . '_' . $end_day . '_' . $category_id . '.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$categoryObj = Factory::getModel('category');
			$category = $categoryObj->getAll();

			$sql =  ' SELECT tr.category_id AS id, tc.title AS `name`, SUM(td.amount * td.exchange_rate) AS `value` FROM ' . $this->_prefix . 'detail AS td ' . 
					' JOIN ' . $this->_prefix . 'record AS tr ON tr.id = td.record_id ' .
					' JOIN ' . $this->_prefix . 'category AS tc ON tc.id = tr.category_id' .
					' WHERE 1 = 1 ';

			if($start_day > 0)
			{
				$sql .= ' AND td.create_time >= ' . strtotime(date('Y-m-d', $start_day));
			}

			if($end_day > 0)
			{
				$sql .= ' AND td.create_time <= ' . (strtotime(date('Y-m-d', $end_day)) + 86399);
			}

			if($category_id != 0)
			{
				$ids = [];
				if($category[$category_id]['parent_id'] == 0)
				{
					foreach($category as $v)
					{
						if($v['parent_id'] == $category_id)
						{
							$ids[] = $v['id'];
						}
					}
				}
				$ids[] = $category_id;
				$sql .= ' AND tr.category_id IN (' . implode(',', $ids) . ')';
			}

			$sql .= ' GROUP BY tr.category_id ';
			$temp = $this->fetchRows($sql);

			$result = [];
			if($category_id != 0)
			{
				foreach($temp as $k => $v)
				{
					if($category[$v['id']]['parent_id'] == $category_id)
					{
						$result[] = $v;
					}
					else if($category_id == $v['id'])
					{
						$result[] = ['id' => $v['id'], 'name' => '未分类', 'value' => $v['value']];
					}
				}
			}
			else
			{
				foreach($temp as $k => $v)
				{
					if($category[$v['id']]['parent_id'] == 0)
					{
						$result[$v['id']] = $v;
					}
				}
				foreach($temp as $k => $v)
				{
					if($category[$v['id']]['parent_id'] > 0)
					{
						if(!isset($result[$category[$v['id']]['parent_id']]))
						{
							$result[$category[$v['id']]['parent_id']] = ['id' => $category[$v['id']]['parent_id'], 'name' => $category[$v['id']]['title'], 'value' => 0];
						}
						$result[$category[$v['id']]['parent_id']]['value'] += $v['value'];
					}
				}
				$temp = $result;
				$result = [];
				foreach($temp as $v)
				{
					$result[] = $v;
				}
			}

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		return $result;
	}

	public function address($start_day, $end_day)
	{
		$start_day = ($start_day == 0) ? 0 : strtotime(date('Y-m-d', $start_day));
		$end_day = ($end_day == 0) ? 0 : strtotime(date('Y-m-d', $end_day)) + 86399;

		$filecache = new Filecache();
		$key = 'statistics/address/' . $start_day . '_' . $end_day . '.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$sql =  ' SELECT tr.address_id AS id, ta.title AS `name`, COUNT(td.id) AS `value` FROM ' . $this->_prefix . 'detail AS td ' . 
					' JOIN ' . $this->_prefix . 'record AS tr ON tr.id = td.record_id ' .
					' JOIN ' . $this->_prefix . 'address AS ta ON ta.id = tr.address_id' .
					' WHERE 1 = 1 ';

			if($start_day > 0)
			{
				$sql .= ' AND td.create_time >= ' . $start_day;
			}

			if($end_day > 0)
			{
				$sql .= ' AND td.create_time <= ' . $end_day;
			}

			$sql .= ' GROUP BY tr.address_id ';
			$result = $this->fetchRows($sql);

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		return $result;
	}

	public function incexpByMonthGroupMonth($start_month, $end_month, $type = 'expenditure')
	{
		$start_month = strtotime(date('Y-m-01', strtotime($start_month)));
		$end_month = strtotime(date('Y-m-01', strtotime(date('Y-m-01', strtotime($end_month))) + 32 * 86400)) - 1;
		$type = in_array($type, ['income', 'expenditure']) ? $type : 'expenditure';

		$filecache = new Filecache();
		$key = 'statistics/incexpByMonthGroupMonth/' . $start_month . '_' . $end_month . '_' . $type . '.temp';
		$result = $filecache->get($key);
		if(!$result)
		{

			$sql =  ' SELECT ' .
					'	FROM_UNIXTIME(td.create_time, "%y.%m") AS `name`, ' ;
			if($type == 'expenditure')
			{
				$sql .= ' SUM(IF(td.amount < 0, td.amount * td.exchange_rate, 0)) AS value ';
			}
			else
			{
				$sql .= ' SUM(IF(td.amount > 0, td.amount * td.exchange_rate, 0)) AS value ';
			}
			$sql .= ' FROM ' . $this->_prefix . 'detail AS td ' .
					' WHERE td.create_time BETWEEN ' . $start_month . ' AND ' . $end_month . 
					' GROUP BY `name` ';
			$temp = $this->fetchRows($sql);
			$result = [];
			foreach($temp as $v)
			{
				$result[$v['name']] = $v;
			}
			$now_month = $start_month;
			while(true)
			{
				$k = date('y.m', $now_month);
				if(!isset($result[$k]))
				{
					$result[$k] = ['name' => $k, 'value' => 0];
				}
				$now_month = strtotime(date('Y-m-01', strtotime(date('Y-m-01', $now_month)) + 32 * 86400));
				if($now_month > $end_month)
				{
					break;
				}
			}
			$result = array_values($result);
			$c = count($result);
			for($i = 0; $i < $c; $i++)
			{
				for($j = $i; $j < $c; $j++)
				{
					if(strtotime($result[$i]['name']) > strtotime($result[$j]['name']))
					{
						$t = $result[$i];
						$result[$i] = $result[$j];
						$result[$j] = $t;
					}
				}
			}

			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		return $result;
	}
}
