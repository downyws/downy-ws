<?php
class ActionFollower extends Action
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
				'search' => [['format', 'trim']],
				'state' => [['valid', 'in', '', null, [1, 2]]],
				'sort_field' => [['valid', 'in', '', '', ['id', 'nickname', 'level', 'state', 'create_time']]],
				'sort_type' => [['valid', 'in', '', '', ['asc', 'ASC', 'desc', 'DESC']]]
			]);

			$followerObj = Factory::getModel('follower');
			$result = $followerObj->getPageList($params['index'], $params, ['field' => $params['sort_field'], 'type' => $params['sort_type']]);
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

		$followerObj = Factory::getModel('follower');

		if(empty($_POST))
		{
			$detail = $followerObj->getDetail($params['id']);
			$this->assign('detail', $detail);
		}
		else
		{
			$params = $this->_submit->obtain($_REQUEST, [
				'id' => [['valid', 'int', '', 0, null], ['valid', 'gte', '', 0, 1]],
				'nickname' =>  [['format', 'trim'], ['valid', 'empty', '昵称不能为空', null, null]],
				'level' => [['valid', 'int', '', 0, null], ['valid', 'between', '权限不能超出[0-100]', null, [0, 100]]]
			]);

			if(!empty($this->_submit->errors))
			{
				$result = ['success' => false, 'message' => implode('；', $this->_submit->errors)];
			}
			else if($followerObj->save($params))
			{
				$result = ['success' => true];
			}
			else
			{
				$result = ['success' => false, 'message' => '保存失败'];
			}
			echo json_encode($result);
			exit;
		}
	}
}
