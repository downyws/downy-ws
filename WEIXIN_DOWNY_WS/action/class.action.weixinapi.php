<?php
class ActionWeixinApi extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodValid()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'signature' => array(array('format', 'trim')),
			'timestamp' => array(array('format', 'trim')),
			'nonce' => array(array('format', 'trim')),
			'echostr' => array(array('format', 'trim'))
		));
		file_put_contents('D:/1.txt', json_encode($params));
	}
}
