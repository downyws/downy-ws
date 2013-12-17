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
		Front::redirect(MAP_URL);
	}
}
