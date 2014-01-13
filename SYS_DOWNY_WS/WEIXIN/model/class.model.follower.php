<?php
class ModelFollower extends Model
{
	public $_table = 'follower';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getPageList($page, $nickname)
	{
		$list = array();
		$condition = array();
		if($nickname != '')
		{
			$condition[] = array('nickname' => array('like', $nickname));
		}

		$sql = 'SELECT * FROM ' . $this->_prefix . $this->_table . 
				$this->getWhere($condition) .
				' ORDER BY nickname ASC ' .
				$this->getLimit($page);
		$list['datas'] = $this->fetchRows($sql);

		$count = $this->getOne($condition, 'COUNT(*)');
		$list['pager'] = $this->getPager($page, $count);

		return $list;
	}

	public function getDetail($id)
	{
		$condition = array();
		$condition[] = array('id' => array('eq', $id));
		return $this->getObject($condition);
	}

	public function save($detail)
	{
		$data = array();
		if(isset($detail['nickname']))
		{
			$data['nickname'] = $detail['nickname'];
		}
		if(isset($detail['level']))
		{
			$data['level'] = $detail['level'];
		}
		if(isset($detail['state']))
		{
			$data['state'] = $detail['state'];
		}

		if(!empty($data))
		{
			$condition = array();
			$condition[] = array('id' => array('eq', $detail['id']));
			return $this->update($condition, $data);
		}
		return false;
	}
}
