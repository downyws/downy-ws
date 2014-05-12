<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='';

	public function renderJson($object)
	{
		echo CJavaScript::jsonEncode($object);
		Yii::app()->end();
	}
}