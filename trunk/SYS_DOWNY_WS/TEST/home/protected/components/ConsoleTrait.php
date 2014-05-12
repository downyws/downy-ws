<?php

trait ConsoleTrait
{
	public function beforeAction($action)
	{
		$this->layout = 'console';
		return parent::beforeAction($action);
	}
}