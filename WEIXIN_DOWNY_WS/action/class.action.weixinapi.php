<?php
class ActionWeixinApi extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndexApi()
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
		else if(!empty($GLOBALS["HTTP_RAW_POST_DATA"]))
		{
			$weixinApiObj = Factory::getModel('weixinApi');
			$response = $weixinApiObj->getResponse($GLOBALS["HTTP_RAW_POST_DATA"]);
			$this->initTemplate(false);
			if($response['msgType'] != 'text')
			{
				$response['content'] = json_decode($response['content'], true);
			}
			$this->assign('response', $response);
			echo $this->_tpl->fetch('weixinapi_response.html');
		}
		else
		{
			echo $params['echostr'];
		}
	}
}
