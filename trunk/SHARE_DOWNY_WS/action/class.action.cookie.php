<?php
class ActionCookie extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodSet()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'expire' => array(array('valid', 'regex', '', 'hld', '/^([0-9]+)|(max)|(del)$/')),
			'key' => array(array('format', 'trim')),
			'val' => array(array('format', 'trim')),
			'callback' => array(array('format', 'trim'))
		));

		switch($params['expire'])
		{
			case 'max':
				setcookie($params['key'], $params['val'], time() + 86400000, '', APP_DOMAIN);
				break;
			case 'del':
				setcookie($params['key'], '', 1, '', APP_DOMAIN);
				break;
			case 'now':
				setcookie($params['key'], $params['val'], 0, '', APP_DOMAIN);
				break;
			case 'hld':
				break;
			default:
				setcookie($params['key'], $params['val'], time() + intval($params['expire']), '', APP_DOMAIN);
				break;
		}
		
		if(!empty($params['callback']))
		{
			$this->redirect($params['callback']);
		}
		exit;
	}
}
