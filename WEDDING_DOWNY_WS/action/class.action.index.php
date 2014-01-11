<?php
class ActionIndex extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{

	}

	public function methodMap()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'location' => array(array('valid', 'in', null, 'x', array('x', 'j')))
		));
		if($params['location'] == 'j')
		{
			Front::redirect(MAP_URL_J);
		}
		else
		{
			Front::redirect(MAP_URL_X);
		}
	}
}
