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
		$params = $this->_submit->obtain($_REQUEST, [
			'location' => [['valid', 'in', null, 'x', ['x', 'j']]]
		]);
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
