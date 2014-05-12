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
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$errors = [];
			$configs = [];
			foreach($_POST as $k => $v)
			{
				$config = Config::model()->findByAttributes(['key' => $k]);
				if($config)
				{
					if(trim($v) == '')
					{
						if(!isset($errors[$k]))
						{
							$errors[$k] = [];
						}
						$errors[$k][] = '请填写值';
					}
					else
					{
						$config['value'] = trim($v);
						$configs[] = $config;
					}
				}
			}
			if($errors)
			{
				$this->renderJson(['success' => false, 'errors' => $errors]);
			}

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				foreach($configs as $v)
				{
					if(!$v->save())
					{
						throw new Exception('保存失败');
					}
				}
				$transaction->commit();
				$this->renderJson(['success' => true]);
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->renderJson(['success' => false, 'message' => $e->getMessage()]);
			}
		}

		$config = Config::model()->findAll();
		$this->render('system', [
			'title' => '系统设置',
			'config' => $config
		]);
	}
}