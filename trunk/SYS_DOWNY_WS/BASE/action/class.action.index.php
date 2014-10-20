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

	public function methodWelcome()
	{
	}

	public function methodNavi()
	{
		$access = $GLOBALS['CONFIG']['ACCESS'];
		$sites = $GLOBALS['CONFIG']['SITES'];
		foreach($sites as $k => $v)
		{
			$sites[$k]['url'] = isset($access[$k]) ? $access[$k]['URL'] : '';
		}
		$this->assign('sites', $sites);
	}

	public function methodMessage()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'code' => [['format', 'trim']],
			'data' => [['format', 'trim']]
		]);

		$MESSAGE = $GLOBALS['CONFIG']['MESSAGE'];

		if(isset($MESSAGE[$params['code']]))
		{
			$message = $MESSAGE[$params['code']];
		}
		else
		{
			$message = $MESSAGE['UNKNOW_CODE'];
		}
		if(!is_string($message['DETAIL']))
		{
			$message['DETAIL'] = $message['DETAIL']($params);
		}

		$this->assign('message', $message);
	}
}
