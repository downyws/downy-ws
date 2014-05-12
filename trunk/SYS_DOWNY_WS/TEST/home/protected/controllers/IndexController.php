<?php

class IndexController extends Controller 
{
	public function beforeAction($action)
	{
		$this->layout = 'front';
		return parent::beforeAction($action);
	}

	public function actionIndex()
	{
		$this->render('index');
	}
}