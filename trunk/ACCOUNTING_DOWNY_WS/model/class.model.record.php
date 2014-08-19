<?php
class ModelRecord extends Model
{
	public $_table = 'record';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getRecord($id)
	{
		$result = [];

		$result = $this->getObject([['id' => ['eq', $id]]]);
		if($result != null)
		{
			$result['surplus'] = sprintf('%.6f', $result['surplus']);
			$result['address'] = $this->getObject([['id' => ['eq', $result['address_id']]]], [], 'address');
			$result['detail'] = $this->getObjects([['record_id' => ['eq', $id]]], [], 'detail');
			foreach($result['detail'] as $k => $v)
			{
				$result['detail'][$k]['amount'] = sprintf('%.2f', $result['detail'][$k]['amount']);
				$result['detail'][$k]['file'] = $this->getObjects([['detail_id' => ['eq', $v['id']]]], ['id', 'detail_id', 'title', 'hash', 'create_time'], 'file');
			}
		}
		else
		{
			$result = [
				'id' => 0,
				'category_id' => 0,
				'address_id' => 0,
				'surplus' => 'N/A',
				'surplus_currency_id' => 0,
				'remark' => '',
				'create_time' => 0,
				'remind_time' => 0,
				'finish_time' => 0,
				'state' => 0,
				'address' => ['id' => 0, 'title' => '', 'detail' => '', 'lon' => '', 'lat' => '', 'type' => 0],
				'detail' => []
			];
		}

		return $result;
	}

	public function getList($start_index, $page_size = PAGE_SIZE)
	{
		$categoryObj = Factory::getModel('category');
		$currencyObj = Factory::getModel('currency');
		$categorys = $categoryObj->getAll();
		$currencys = $currencyObj->getAll();

		$sql  = ' SELECT tr.*, ta.title AS address_title, ta.detail AS address_detail, ta.lon AS address_lon, ta.lat AS address_lat, ' .
				'	IF(tr.remind_time > 0 AND tr.remind_time <= ' . time() . ', tr.remind_time, ' . time() . ') AS remind ' .
				' FROM ' . $this->_prefix . 'record AS tr ' .
				' JOIN ' . $this->_prefix . 'address AS ta ON ta.id = tr.address_id ' .
				' ORDER BY remind ASC, state DESC, create_time DESC ' .
				' LIMIT ' . ($start_index - 1) . ', ' . $page_size;
		$result = $this->fetchRows($sql);
		$temp = [];
		foreach($result as $k => $v)
		{
			$temp[] = $v['id'];
			$result[$k]['detail_count'] = 0;
			$result[$k]['file_count'] = 0;

			$result[$k]['category_title'] = $categorys[$v['category_id']]['title'];
			if($categorys[$v['category_id']]['parent_id'] > 0)
			{
				$parent_id = $categorys[$v['category_id']]['parent_id'];
				$result[$k]['category_parent_title'] = $categorys[$parent_id]['title'];
			}

			$result[$k]['surplus'] = sprintf('%.6f', $result[$k]['surplus']);
			$result[$k]['surplus_currency_abbr'] = $currencys[$v['surplus_currency_id']]['abbr'];
		}

		if(!empty($temp))
		{
			$sql =  ' SELECT td.record_id, COUNT(*) FROM ' . $this->_prefix . 'file AS tf ' .
					' JOIN ' . $this->_prefix . 'detail AS td ON td.id = tf.detail_id ' .
					' WHERE td.record_id IN (' . implode(',', $temp) . ') ' .
					' GROUP BY td.record_id ';
			$temp_file = $this->fetchPairs($sql);

			$sql =  ' SELECT td.record_id, COUNT(*) FROM ' . $this->_prefix . 'detail AS td ' .
					' WHERE td.record_id IN (' . implode(',', $temp) . ') ' .
					' GROUP BY td.record_id ';
			$temp_detail = $this->fetchPairs($sql);

			foreach($result as $k => $v)
			{
				if(isset($temp_file[$v['id']]))
				{
					$result[$k]['file_count'] = $temp_file[$v['id']];
				}
				if(isset($temp_detail[$v['id']]))
				{
					$result[$k]['detail_count'] = $temp_detail[$v['id']];
				}
			}
		}

		return $result;
	}

	public function getStatistics()
	{
		$filecache = new Filecache();
		$key = 'record_statistics.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$result = [];
			$sql =  ' SELECT d.amount * exchange_rate FROM ' . $this->_prefix . 'record AS r ' .
					' JOIN ' . $this->_prefix . 'detail AS d ON r.id = d.record_id ' .
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

	public function remove($record_id)
	{
		try
		{
			// 开始事务
			$this->transStart();

			if($this->delete([['id' => ['eq', $record_id]]], null, 'record') === false)
			{
				throw new Exception('删除记录失败');
			}

			$detail_ids = $this->getCol([['record_id' => ['eq', $record_id]]], 'id', 'detail');
			if($this->delete([['record_id' => ['eq', $record_id]]], null, 'detail') === false)
			{
				throw new Exception('删除明细失败');
			}

			if($this->delete([['detail_id' => ['in', $detail_ids]]], null, 'file') === false)
			{
				throw new Exception('删除凭证失败');
			}

			// 提交事务
			if(!$this->transCommit())
			{
				throw new Exception('保存事务失败');
			}
		}
		catch(Exception $e)
		{
			$this->transRollback();
			return ['message' => $e->getMessage()];
		}

		return [];
	}

	public function save($data)
	{
		// 记录检查
		if($data['category_id'] < 1)
		{
			return ['message' => '分类 解析错误'];
		}
		else if(strtotime($data['create_time']) === false)
		{
			return ['message' => '发生时间 解析错误'];
		}
		else if($data['address']['title'] == '')
		{
			return ['message' => '地址简称 解析错误'];
		}
		else if(!in_array($data['state'], [1, 2]))
		{
			return ['message' => '记录状态 解析错误'];
		}
		else if($data['finish_time'] != '' && strtotime($data['finish_time']) === false)
		{
			return ['message' => '完成时间 解析错误'];
		}
		else if($data['remind_time'] != '' && strtotime($data['remind_time']) === false)
		{
			return ['message' => '提醒时间 解析错误'];
		}

		// 明细检查
		if(!isset($data['detail']) || count($data['detail']) == 0)
		{
			return ['message' => '至少有一条明细记录'];
		}
		$index = 0;
		foreach($data['detail'] as $v)
		{
			$index++;
			if(strtotime($v['create_time']) === false)
			{
				return ['message' => '第' . $index . '条记录的 发生时间 解析错误'];
			}
			else if($v['amount_currency_id'] < 1)
			{
				return ['message' => '第' . $index . '条记录的 所用货币 解析错误'];
			}
			else if(!is_numeric($v['amount']))
			{
				return ['message' => '第' . $index . '条记录的 金额 解析错误'];
			}
			else if($v['exchange_rate'] != '' && !is_numeric($v['exchange_rate']))
			{
				return ['message' => '第' . $index . '条记录的 结余汇率 解析错误'];
			}
		}

		try
		{
			// 开始事务
			$this->transStart();

			// 地址
			$address_id = $this->getOne([['title' => ['eq', $data['address']['title']]]], 'id', 'address');
			$address = [
				'title' => $data['address']['title'],
				'detail' => $data['address']['detail'],
				'lon' => $data['address']['lon'],
				'lat' => $data['address']['lat'],
				'type' => $data['address']['type']
			];
			if($address_id > 0)
			{
				if($this->update([['id' => ['eq', $address_id]]], $address, 'address') === false)
				{
					throw new Exception('更新地址失败');
				}
			}
			else
			{
				$address_id = $this->insert($address, 'address');
				if(!$address_id)
				{
					throw new Exception('保存地址失败');
				}
			}

			// 记录
			$record = [
				'category_id' => $data['category_id'],
				'address_id' => $address_id,
				'surplus' => 0,
				'surplus_currency_id' => DEFAULT_SURPLUS_CURRENCY,
				'remark' => isset($data['remark']) ? $data['remark'] : '',
				'create_time' => strtotime($data['create_time']),
				'remind_time' => ($data['remind_time'] == '') ? 0 : strtotime($data['remind_time']),
				'finish_time' => ($data['finish_time'] == '') ? 0 : strtotime($data['finish_time']),
				'state' => $data['state']
			];
			if($data['id'] > 0)
			{
				$record_id = $data['id'];
				if($this->update([['id' => ['eq', $record_id]]], $record, 'record') === false)
				{
					throw new Exception('更新记录失败');
				}
			}
			else
			{
				$record_id = $this->insert($record, 'record');
				if(!$record_id)
				{
					throw new Exception('保存记录失败');
				}
			}

			// 明细
			$use_detail_id = [];
			foreach($data['detail'] as $v)
			{
				$detail = [
					'record_id' => $record_id,
					'amount' => $v['amount'],
					'amount_currency_id' => $v['amount_currency_id'],
					'exchange_rate' => is_numeric($v['exchange_rate']) ? $v['exchange_rate'] : $this->selExchangeRate($v['amount_currency_id'], strtotime($v['create_time'])),
					'remark' => isset($v['remark']) ? $v['remark'] : '',
					'create_time' => strtotime($v['create_time'])
				];
				if($v['id'] > 0)
				{
					$detail_id = $v['id'];
					if($this->update([['id' => ['eq', $detail_id]]], $detail, 'detail') === false)
					{
						throw new Exception('更新明细失败');
					}
				}
				else
				{
					$detail_id = $this->insert($detail, 'detail');
					if(!$detail_id)
					{
						throw new Exception('保存明细失败');
					}
				}
				$use_detail_id[] = $detail_id;

				// 凭证
				$use_file_id = [];
				if(isset($v['files']))
				{
					foreach($v['files'] as $file_id)
					{
						if($this->update([['id' => ['eq', $file_id]]], ['detail_id' => $detail_id], 'file') === false)
						{
							throw new Exception('标记凭证失败');
						}
						$use_file_id[] = $file_id;
					}
				}

				// 删除不用的凭证
				$condition = [];
				$condition[] = ['detail_id' => ['eq', $detail_id]];
				if(!empty($use_file_id))
				{
					$condition[] = ['id' => ['not in', $use_file_id]];
				}
				if($this->delete($condition, null, 'file') === false)
				{
					throw new Exception('删除旧凭证失败');
				}
			}

			// 删除不用的明细
			$condition = [];
			$condition[] = ['record_id' => ['eq', $record_id]];
			$condition[] = ['id' => ['not in', $use_detail_id]];
			if($this->delete($condition, null, 'detail') === false)
			{
				throw new Exception('删除旧明细失败');
			}

			// 结余金额计算
			if(!$this->updSurplus($record_id))
			{
				throw new Exception('计算结余金额失败');
			}

			// 提交事务
			if(!$this->transCommit())
			{
				throw new Exception('保存事务失败');
			}
		}
		catch(Exception $e)
		{
			$this->transRollback();
			return ['message' => $e->getMessage()];
		}

		return ['id' => $record_id];
	}

	public function updSurplus($id)
	{
		$sql = 'UPDATE ' . $this->_prefix . 'record SET surplus = (SELECT SUM(amount * exchange_rate) FROM ' . $this->_prefix . 'detail WHERE record_id = ' . $id . ') WHERE id = ' . $id;
		$res = $this->query($sql);

		if($res !== false)
		{
			return $this->affectedRows();
		}
		return false;
	}

	public function selExchangeRate($currency_id, $date_time)
	{
		$temp = $this->getOne([['id' => ['eq', $currency_id]]], 'exchange_rate_log', 'currency');
		$temp = explode("\n", $temp);
		$item = null;
		foreach($temp as $v)
		{
			$v = trim($v);
			$v = explode("\t", $v);
			if(count($v) == 2 && strtotime($v[0]) !== false && is_numeric($v[1]))
			{
				$item = [strtotime($v[0]), $v[1]];
				if($item[0] < $date_time)
				{
					return $item[1];
				}
			}
		}

		if($item == null)
		{
			return $this->getOne([['id' => ['eq', $currency_id]]], 'exchange_rate', 'currency');
		}

		return $item[1];
	}
}
