<?php
class ActionAccess extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodSet()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'app_name' => array(array('format', 'trim')),
			'app_url' => array(array('valid', 'url', '', '', null)),
			'callback' => array(array('valid', 'url', '', '', null))
		));

		if(empty($params['callback']) || empty($params['app_url']))
		{
			$this->redirect('/');
		}

		$this->assign('params', $params);
	}

	public function methodSetAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'password' => array(array('valid', 'empty', array('error' => array('code' => '', 'msg' => 'Please input password.')), null, null)),
			'app_url' => array(array('valid', 'in', array('error' => array('code' => '', 'msg' => 'Application url error.')), null, array_keys($GLOBALS['CONFIG']['ACCESS']))),
			'callback' => array(array('valid', 'url', array('error' => array('code' => '', 'msg' => 'Callback url error.')), null, null))
		));

		if(count($this->_submit->errors) > 0)
		{
			$result = current($this->_submit->errors);
			echo json_encode($result);
			exit;
		}

		$trysafeObj = Factory::getModel('trysafe');

		if($trysafeObj->isMax('ACCESS'))
		{
			$trysafeObj->punish('ACCESS');
			$result = array('error' => array('code' => '', 'msg' => 'max try.'));

		}
		else if(!in_array($params['password'], $GLOBALS['CONFIG']['ACCESS'][$params['app_url']]['PASSWORDS']))
		{
			$trysafeObj->goUp('ACCESS');
			$result = array('error' => array('code' => '', 'msg' => 'Password Incorrect.'));
		}
		else
		{
			$url = $params['app_url'] . 'index.php?a=access&m=set&t=api&s=PC';
			$post = array(
				'password' => md5(REMOTE_IP_ADDRESS . rand()),
				'expire' => 60,
				'ip' => REMOTE_IP_ADDRESS,
				'timestamp' => time(),
				'useragent' => REMOTE_HTTP_USERAGENT,
				'sign' => ''
			);
			$post['sign'] = md5(base64_encode(
				$GLOBALS['CONFIG']['ACCESS'][$params['app_url']]['API_KEY'] . 
				$post['password'] . 
				$post['expire'] . 
				$post['ip'] . 
				$post['timestamp'] . 
				$post['useragent']
			));
			Factory::loadLibrary('curlhelper');
			$curlhelper = new CurlHelper($GLOBALS['CONFIG']['CURL']);
			$response = $curlhelper->request($url, $post);
			$response = json_decode($response['body'], true);
			if(isset($response['error']))
			{
				$trysafeObj->goUp('ACCESS');
				$result = array('error' => array('code' => '', 'msg' => 'Please try again later.'));
			}
			else
			{
				$trysafeObj->clear('ACCESS');
				$url = $params['app_url'] . 'index.php?a=cookie&m=set&s=PC' . 
						'&expire=now' .
						'&key=ACCESS' .
						'&val=' . $post['password'] .
						'&callback=' . urlencode($params['callback']);
				$result = array('url' => $url);
			}
		}

		echo json_encode($result);
		exit;
	}
}
