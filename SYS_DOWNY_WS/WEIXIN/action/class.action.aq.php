<?php
class ActionAQ extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		if(!empty($_POST))
		{
			$params = $this->_submit->obtain($_POST, [
				'index' => [['valid', 'gte', '', 1, 1]],
				'is_adjust' => [['valid', 'empty', '', null, null], ['valid', 'in', '', null, [0, 1]]],
				'search' => [['format', 'trim']],
				'sort_field' => [['valid', 'in', '', '', ['id', 'val', 'is_adjust']]],
				'sort_type' => [['valid', 'in', '', '', ['asc', 'ASC', 'desc', 'DESC']]]
			]);

			$aqObj = Factory::getModel('aq');
			$result = $aqObj->getPageList($params['index'], $params, ['field' => $params['sort_field'], 'type' => $params['sort_type']]);
			if(!$result)
			{
				$result = ['message' => 'search data error.'];
			}
			echo json_encode($result);
			exit;
		}
	}

	public function methodEdit()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'id' => [['valid', 'int', '', 0, null], ['valid', 'gte', '', 0, 1]],
		]);

		$aqObj = Factory::getModel('aq');

		if(empty($_POST))
		{
			$detail = $aqObj->getDetail($params['id']);
			$this->assign('detail', $detail);
		}
		else
		{
			$params['answer'] = $this->_submit->obtainArray($_REQUEST, [
				'a_id' => [['valid', 'int', '回答编号错误', null, null], ['valid', 'gte', '回答编号错误', null, 0]],
				'aq_is_adjust' => [['valid', 'in', '', '0', ['0', '1']]],
				'a_msg_type' => [['valid', 'in', '', 'text', ['text', 'news']]],
				'a_val' => [['format', 'trim'], ['valid', 'empty', '回答不能为空。', null, null]],
				'aq_level' => [['valid', 'int', '', 0, null], ['valid', 'between', '优先级不能超出[0-100]', null, [0, 100]]],
				'aq_operation' => [['valid', 'in', '', 'hold', ['hold', 'del']]]
			]);

			if(!empty($this->_submit->errors))
			{
				$result = ['success' => false, 'message' => implode('；', $this->_submit->errors)];
			}
			else
			{
				$result = $aqObj->save($params);
				$result = ($result === true) ? ['success' => true] : ['success' => false, 'message' => $result];
			}
			echo json_encode($result);
			exit;
		}
	}
}
