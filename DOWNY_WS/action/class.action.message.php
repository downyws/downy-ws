<?php
class ActionMessage extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodWarning()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'code' => [['format', 'trim']],
			'data' => [['format', 'trim']]
		]);

		$WARNING = $GLOBALS['CONFIG']['MESSAGE']['WARNING'];

		if(isset($WARNING[$params['code']]))
		{
			$message = $WARNING[$params['code']];
		}
		else
		{
			$message = $WARNING['UNKNOW_CODE'];
			$params['data'] = '[code:' . $params['code'] . ']';
			$params['code'] = 'UNKNOW_CODE';
		}

		$this->assign('message', $message);
		$this->assign('params', $params);
	}
}
