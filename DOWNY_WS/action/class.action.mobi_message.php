<?php
class ActionMobi_Message extends ActionMessage
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodPCSiteWarning()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'app_name' => [['format', 'trim']],
			'callback' => [['valid', 'url', '', '', null]],
			'only_pc' => [['format', 'int']],
			'close_warning' => [['format', 'int']]
		]);

		if(!empty($_POST))
		{
			$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);
			if(!isset($SITE_SETTING[$params['app_name']]))
			{
				$SITE_SETTING[$params['app_name']] = ['TYPE' => 'PC'];
			}
			$SITE_SETTING[$params['app_name']]['CLOSE_WARNING'] = time() + ($params['close_warning'] ? 86400000 : 86400);
			setcookie('SITE_SETTING', json_encode($SITE_SETTING), time() + 86400000, '/', ROOT_DOMAIN);
			Front::redirect($params['callback']);
		}

		$this->assign('params', $params);
	}
}
