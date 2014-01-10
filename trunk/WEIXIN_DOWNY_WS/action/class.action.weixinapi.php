<?php
class ActionWeixinApi extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodValidApi()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'signature' => array(array('format', 'trim')),
			'timestamp' => array(array('valid', 'between', 'timestamp out.', null, array(time() - 30, time() + 30))),
			'nonce' => array(array('format', 'trim')),
			'echostr' => array(array('format', 'trim'))
		));

		if(count($this->_submit->errors) > 0)
		{
			echo current($this->_submit->errors);
			exit;
		}

		$sign = array(WEIXIN_TOKEN, $params['timestamp'], $params['nonce']);
		sort($sign);
		$sign = implode($sign);

		if($params['signature'] != sha1($sign))
		{
			echo 'signature error.';
		}
		else
		{
			echo $params['echostr'];
		}
	}
}
