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
		}
		else if($params['signature'] == sha1(WEIXIN_TOKEN . $params['timestamp'] . $params['nonce']))
		{
			echo $params['echostr'];
		}
		else
		{
			echo 'signature error.';
		}
	}
}
