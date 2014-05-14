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
		$documents = Yii::app()->cache->get('index_page_documents');
		if($documents === false)
		{
			$documents = [];
			$temp = Document::model()->findAll();

			foreach($temp as $v)
			{
				$documents[$v['code']] = $v;
			}
			Yii::app()->cache->set('index_page_documents', $documents, 300);
		}
		$this->render('index', ['documents' => $documents]);
	}
}