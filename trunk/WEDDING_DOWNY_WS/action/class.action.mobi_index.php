<?php
class ActionMobi_Index extends ActionIndex
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodWeixinMap()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'location' => array(array('valid', 'in', null, 'x', array('x', 'j')))
		));
		$this->assign('params', $params);
	}
}
