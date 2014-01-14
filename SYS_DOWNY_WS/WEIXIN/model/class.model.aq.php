<?php
class ModelAQ extends Model
{
	public $_table = 'aq';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getAid($val)
	{
		$condition = array();
		$condition[] = array('val' => array('eq', $val));
		$id = $this->getOne($condition, 'id', 'answer');
		if(!$id)
		{
			$data = array('val' => $val);
			$id = $this->insert($data, 'answer');
		}
		return $id;
	}

	public function getDetail($id)
	{
		$condition = array();
		$condition[] = array('id' => array('eq', $id));
		$detail = $this->getObject($condition, array(), 'question');
		if($detail)
		{
			$sql =	' SELECT a.*, aq.level, aq.is_adjust FROM weixin_aq AS aq ' .
					' JOIN weixin_answer AS a ON a.id = aq.a_id ' .
					' WHERE aq.q_id = ' . $detail['id'] .
					' ORDER BY aq.level DESC, a.val ASC ';
			$detail['answer'] = $this->fetchRows($sql);
		}
		return $detail;
	}

	public function getPageList($page, $params)
	{
		$list = array();
		$condition = array();
		if($params['question'] != '')
		{
			$condition[] = array('val' => array('like', $params['question']));
		}
		switch($params['is_adjust'])
		{
			case 'true':
				$condition[] = array('is_adjust' => array('eq', 1));
				break;
			case 'false':
				$condition[] = array('is_adjust' => array('eq', 0));
				break;
		}

		$sql = 'SELECT * FROM ' . $this->_prefix . 'question' . 
				$this->getWhere($condition) .
				' ORDER BY val ASC ' .
				$this->getLimit($page);
		$list['datas'] = $this->fetchRows($sql);

		$count = $this->getOne($condition, 'COUNT(*)', 'question');
		$list['pager'] = $this->getPager($page, $count);

		return $list;
	}

	public function save($detail)
	{
		$fields = array('q_id', 'a_id', 'level', 'is_adjust');
		$datas = array();
		$conditions = array();
		foreach($detail['answer'] as $v)
		{
			$conditions[] = array(
				array('q_id' => array('eq', $detail['id'])),
				array('a_id' => array('eq', $v['a_id']))
			);
			if($v['aq_need_del'] == 'hld')
			{
				$a_id = $this->getAid($v['a_val']);
				if($a_id)
				{
					$datas[] = array('q_id' => $detail['id'], 'a_id' => $a_id, 'level' => $v['aq_level'], 'is_adjust' => ($v['aq_is_adjust'] == '1' ? 1 : 0));
				}
				else
				{
					return false;
				}
			}
		}
		foreach($conditions as $v)
		{
			$this->delete($v, array());
		}
		if(!empty($datas))
		{
			$this->insertBatch($fields, $datas);
		}

		$condition = array();
		$condition[] = array('q_id' => array('eq', $detail['id']));
		$condition[] = array('is_adjust' => array('eq', 0));
		$is_adjust = $this->getOne($condition, 'COUNT(*)') ? 0 : 1;

		$condition = array();
		$condition[] = array('id' => array('eq', $detail['id']));
		$data = array('is_adjust' => $is_adjust);
		$this->update($condition, $data, 'question');

		return true;
	}
}
