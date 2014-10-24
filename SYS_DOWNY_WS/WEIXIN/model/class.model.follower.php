<?php
class ModelFollower extends Model
{
	public $_table = 'follower';

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
			$condition[] = ['nickname' => ['like', $filter['search']]];
		}
		if(isset($filter['state']))
		{
			$condition[] = ['state' => ['eq', $filter['state']]];
		}
		$sql = 'SELECT * FROM ' . $this->_prefix . $this->_table . $this->getWhere($condition);
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
		return $this->getObject($condition);
	}

	public function save($detail)
	{
		$data = [];
		if(isset($detail['nickname']))
		{
			$data['nickname'] = $detail['nickname'];
		}
		if(isset($detail['level']))
		{
			$data['level'] = $detail['level'];
		}

		if(!empty($data))
		{
			$condition = [];
			$condition[] = ['id' => ['eq', $detail['id']]];
			return $this->update($condition, $data);
		}
		return false;
	}
}
