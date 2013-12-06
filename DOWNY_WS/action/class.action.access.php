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
			$this->redirect(stripos(get_class($this), 'ActionMobi_') === 0 ? '/mobi/' : '/');
		}

		$this->assign('params', $params);
	}

	public function methodSetAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'password' => array(array('valid', 'empty', array('error' => array('code' => '', 'msg' => 'Plz input  password.')), null, null)),
			'app_url' => array(array('valid', 'in', array('error' => array('code' => '', 'msg' => 'invaild app url .')), null, array_keys($GLOBALS['CONFIG']['ACCESS']))),
			'callback' => array(array('valid', 'url', array('error' => array('code' => '', 'msg' => 'invaild callback .')), null, null)),
			'remember' => array(array('valid', 'in', '', '0', array(0, 1)))
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
			$result = array('error' => array('code' => '', 'msg' => 'password error.'));
		}
		else
		{
			$url = $params['app_url'] . 'index.php?a=access&m=set&t=api&s=PC';
			$post = array(
				'password' => md5(REMOTE_IP_ADDRESS . rand()),
				'expire' => $params['remember'] ? 86400000 : 60,
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
				$result = array('error' => array('code' => '', 'msg' => 'wait time.'));
			}
			else
			{
				$trysafeObj->clear('ACCESS');
				$url = $params['app_url'] . 'index.php?a=cookie&m=set&s=PC' . 
						'&expire=' . ($params['remember'] ? 'max' : 'now') . 
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
