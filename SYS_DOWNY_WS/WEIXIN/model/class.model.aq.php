<?php
class ModelAQ extends Model
{
	public $_table = 'aq';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getPageList($index, $filter, $sort)
	{
		$result = [];

		$condition = [];
		if($filter['search'] != '')
		{
			$condition[] = ['val' => ['like', $filter['search']]];
		}
		if(isset($filter['is_adjust']))
		{
			$condition[] = ['is_adjust' => ['eq', $filter['is_adjust']]];
		}
		$sql = 'SELECT * FROM ' . $this->_prefix . 'question' . $this->getWhere($condition);
		if(!empty($sort['field']) && !empty($sort['type']))
		{
			$sql .= ' ORDER BY ' . $sort['field'] . ' ' . $sort['type'];
		}
		$sql .= ' LIMIT ' . ($index - 1) . ', ' . APP_PAGER_SIZE;

		$result['datas'] = $this->fetchRows($sql);
		$result['amount'] = $this->getOne($condition, 'COUNT(*)');

		return $result;
	}

	public function getDetail($id)
	{
		$condition = [];
		$condition[] = ['id' => ['eq', $id]];
		$detail = $this->getObject($condition, [], 'question');
		if($detail)
		{
			$sql =	' SELECT a.id, IF(a.msg_type = "text", a.val, a.data) AS val, a.msg_type, aq.level, aq.is_adjust FROM ' . $this->_prefix . 'aq AS aq ' .
					' JOIN ' . $this->_prefix . 'answer AS a ON a.id = aq.a_id ' .
					' WHERE aq.q_id = ' . $detail['id'] .
					' ORDER BY aq.level DESC, a.val ASC ';
			$detail['answer'] = $this->fetchRows($sql);
		}
		return $detail;
	}

	public function save($detail)
	{
		try
		{
			$this->transStart();

			$condition = [['q_id' => ['eq', $detail['id']]]];
			$a_ids_hold = [];
			$a_ids_all = $this->getCol($condition, 'a_id');

			// 清除所有aq
			$this->delete($condition);

			// 新建aq
			$fields = ['q_id', 'a_id', 'level', 'is_adjust'];
			$datas = [];
			foreach($detail['answer'] as $v)
			{
				if($v['aq_operation'] == 'hold')
				{
					$a_id = $this->getAid($v['a_val'], $v['a_msg_type']);
					$datas[] = ['q_id' => $detail['id'], 'a_id' => $a_id, 'level' => $v['aq_level'], 'is_adjust' => $v['aq_is_adjust']];
					$a_ids_hold[] = $a_id;
				}
			}
			if(!empty($datas))
			{
				$this->insertBatch($fields, $datas);
			}

			// 清除没有引用的answer
			$a_ids_del = array_diff($a_ids_all, $a_ids_hold);
			if(!empty($a_ids_del))
			{
				$condition = [['a_id' => ['in', $a_ids_del]]];
				$a_ids_use = $this->getCol($condition, 'a_id');
				$a_ids_del = array_diff($a_ids_del, $a_ids_use);
				if(!empty($a_ids_del))
				{
					$condition = [['id' => ['in', $a_ids_del]]];
					$this->delete($condition, null, 'answer');
				}
			}

			$condition = [];
			$condition[] = ['id' => ['eq', $detail['id']]];
			$data = ['is_adjust' => 1];
			$this->update($condition, $data, 'question');

			if(!$this->transCommit())
			{
				throw new Exception('保存事务失败');
			}

			return true;
		}
		catch(Exception $e)
		{
			$this->transRollback();
			return $e->getMessage();
		}
	}

	public function getAid($val, $msg_type)
	{
		$condition = [];
		$condition[] = ($msg_type == 'text') ? ['val' => ['eq', $val]] : ['data' => ['eq', $val]];
		$condition[] = ['msg_type' => ['eq', $msg_type]];
		$id = $this->getOne($condition, 'id', 'answer');

		if(!$id)
		{
			$data = array('msg_type' => $msg_type);
			if($msg_type == 'text')
			{
				$data['val'] = $val;
				$data['data'] = '';
			}
			else
			{
				$data['val'] = '';
				$data['data'] = $val;
			}
			$id = $this->insert($data, 'answer');
		}
		return $id;
	}
}
