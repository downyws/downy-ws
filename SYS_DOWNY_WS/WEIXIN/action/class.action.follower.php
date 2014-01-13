<?php
class ActionFollower extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'p' => array(array('valid', 'gte', '', 1, 1)),
			'nickname' => array(array('format', 'trim'))
		));

		$followerObj = Factory::getModel('follower');
		$list = $followerObj->getPageList($params['p'], $params);
		$this->assign('list', $list);
		$this->assign('params', $params);
		$this->assign('navgurl', '/index.php?a=follower&m=index&nickname=' . urlencode($params['nickname']) . '&p=');
	}

	public function methodEdit()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'id' => array(array('valid', 'gte', '', 0, 1)),
			'save' => array(array('format', 'trim'))
		));

		$followerObj = Factory::getModel('follower');

		if(!empty($params['save']))
		{
			$params = $this->_submit->obtain($_REQUEST, array(
				'id' => array(array('valid', 'gte', '', 0, 1)),
				'nickname' => array(array('format', 'trim')),
				'level' => array(array('valid', 'gte', '', 0, 0)),
				'state' => array(array('valid', 'gte', '', 0, 0)),
			));
			$followerObj->save($params);
		}
		$detail = $followerObj->getDetail($params['id']);
		if(!$detail)
		{
			$this->redirect('', 404);
		}
		$this->assign('detail', $detail);
	}
}
