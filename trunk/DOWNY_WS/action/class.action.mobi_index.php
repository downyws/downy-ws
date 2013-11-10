<?php
class ActionMobi_Index extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$this->redirect('http://www.' . ROOT_DOMAIN . '/mobi/');
	}
}
