<?php
class ActionMobi_Index extends ActionIndex
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodWeixinMap()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'location' => [['valid', 'in', null, 'x', ['x', 'j']]]
		]);
		$this->assign('params', $params);
	}
}
