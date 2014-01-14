<?php
class ActionAQ extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'p' => array(array('valid', 'int', '', 1, null), array('valid', 'gte', '', 1, 1)),
			'question' => array(array('format', 'trim')),
			'is_adjust' => array(array('valid', 'in', '', 'all', array('true', 'false', 'all')))
		));

		$aqObj = Factory::getModel('aq');
		$list = $aqObj->getPageList($params['p'], $params);
		$this->assign('list', $list);
		$this->assign('params', $params);
		$this->assign('navgurl', '/index.php?a=aq&m=index&question=' . urlencode($params['question']) . '&is_adjust=' . $params['is_adjust'] . '&p=');
	}

	public function methodEdit()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'id' => array(array('valid', 'int', '', 0, null), array('valid', 'gte', '', 0, 1)),
			'save' => array(array('format', 'trim'))
		));

		$aqObj = Factory::getModel('aq');

		if(!empty($params['save']))
		{
			$params['answer'] = $this->_submit->obtainArray($_REQUEST, array(
				'a_id' => array(array('valid', 'int', '回答编号错误', null, null), array('valid', 'gte', '回答编号错误', null, 0)),
				'aq_is_adjust' => array(array('valid', 'in', '', '0', array('0', '1'))),
				'a_msg_type' => array(array('valid', 'in', '', 'text', array('text', 'news'))),
				'a_val' => array(array('format', 'trim'), array('valid', 'empty', '回答不能为空。', null, null)),
				'aq_level' => array(array('valid', 'int', '', 0, null), array('valid', 'between', '', 0, array(0, 255))),
				'aq_need_del' => array(array('valid', 'in', '', 'hld', array('hld', 'del')))
			));
			if(count($this->_submit->errors) > 0)
			{
				$result = current($this->_submit->errors);
				echo	"<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />" .
						"<script>alert('" . $result . "');window.history.go(-1);</script>";
				exit;
			}
			else
			{
				$aqObj->save($params);
			}
		}
		$detail = $aqObj->getDetail($params['id']);
		if(!$detail)
		{
			$this->redirect('', 404);
		}
		$this->assign('detail', json_encode($detail));
	}
}
