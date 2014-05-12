<?php
/**
 * 管理功能
 */
class ManageController extends Controller 
{
	use ConsoleTrait;

    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
		return [
			['allow', 'actions' => ['document'], 'roles' => ['document']],
			['allow', 'actions' => ['user'], 'roles' => ['user']],
			['allow', 'actions' => ['system'], 'roles' => ['system']],
			['deny']
		];
	}

	/**
	 * 内容管理
	 */
	public function actionDocument()
	{
		$this->render('document');
	}

	/**
	 * 用户管理
	 */
	public function actionUser()
	{
		$this->render('user');
	}

	/**
	 * 系统设置
	 */
	public function actionSystem()
	{
		$this->render('system');
	}
}