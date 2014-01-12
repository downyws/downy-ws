<?php
class ModelWeixinApi extends Model
{
	public $_table = '';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function addAnswer($question, $answer, $level = 0)
	{
		if(stripos($question, '_') === 0)
		{
			$question = str_replace('_', '', $question);
		}

		$condition = array();
		$condition[] = array('val' => array('eq', $question));
		$q_id = $this->getOne($condition, 'id', 'question');
		if(!$q_id)
		{
			$data = array('val' => $question);
			$q_id = $this->insert($data, 'question');
		}

		$condition = array();
		$condition[] = array('val' => array('eq', $answer));
		$a_id = $this->getOne($condition, 'id', 'answer');
		if(!$a_id)
		{
			$data = array('val' => $answer);
			$a_id = $this->insert($data, 'answer');
		}

		if($q_id && $a_id)
		{
			$data = array('q_id' => $q_id, 'a_id' => $a_id, 'level' => $level);
			return $this->insert($data, 'aq', true);
		}
		return false;
	}

	public function getNickname($openid)
	{
		$condition = array();
		$condition[] = array('openid' => array('eq', $openid));
		$follower = $this->getObject($condition, array(), 'follower');
		if(!empty($follower) && !empty($follower['nickname']))	// 此处有BUG,昵称为"0"
		{
			return $follower['nickname'];
		}
		return $openid;
	}

	public function getFollower($openid)
	{
		$condition = array();
		$condition[] = array('openid' => array('eq', $openid));
		$follower = $this->getObject($condition, array(), 'follower');
		if(!empty($follower) && $follower['state'] == FOLLOWER_STATE_CANCEL)
		{
			$data = array('state' => FOLLOWER_STATE_NORMAL);
			$this->update($condition, $data, 'follower');
		}
		else if(empty($follower))
		{
			$follower = array(
				'openid' => $openid,
				'nickname' => $this->getNickname($openid),
				'level' => 0,
				'state' => FOLLOWER_STATE_NORMAL,
				'create_time' => time()
			);
			$follower['id'] = $this->insert($follower, 'follower');
			if($follower['id'] < 1)
			{
				$follower = null;
			}
		}
		return $follower;
	}

	public function cancelFollow($openid)
	{
		$condition = array();
		$condition[] = array('openid' => array('eq', $openid));
		$data = array('state' => FOLLOWER_STATE_CANCEL);
		$this->update($condition, $data, 'follower');
	}

	public function getResponse($request)
	{
		$data = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);

		$response = array();
		$response['toUserName'] = $data->FromUserName;
		$response['fromUserName'] = $data->ToUserName;
		$response['createTime'] = time();

		$follower = $this->getFollower($data->FromUserName);
		switch($data->MsgType)
		{
			case 'event':
				switch($data->Event)
				{
					case 'subscribe':
						$response['msgType'] = 'text';
						$response['content'] = $this->autoText(ONEVENT_SUBSCRIBE, $follower);
						break;
					case 'unsubscribe':
						$response['msgType'] = 'text';
						$response['content'] = $this->autoText(ONEVENT_UNSUBSCRIBE, $follower);
						$this->cancelFollow($data->FromUserName);
						break;
				}
				break;
			case 'image':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText(ONRECEIVE_IMAGE, $follower);
				break;
			case 'voice':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText(ONRECEIVE_VOICE, $follower);
				break;
			case 'video':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText(ONRECEIVE_VIDEO, $follower);
				break;
			case 'location':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText(ONRECEIVE_LOCATION, $follower);
				break;
			case 'link':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText(ONRECEIVE_LINK, $follower);
				break;
			case 'text':
				$response['msgType'] = 'text';
				$response['content'] = $this->autoText($data->Content, $follower);
				break;
		}

		$log = array(
			'follower_id' => $follower['id'],
			'request' => $request, 
			'response' => json_encode($response),
			'create_time' => $response['createTime']
		);
		$this->insert($log, 'log');

		return $response;
	}

	public function autoText($text, $follower = null)
	{
		// 数学运算判断
		if(preg_match('/^[0-9\+\-\*\/\.\(\)]+$/', $text))
		{
			$text = preg_replace(array('/([0-9])(\()/', '/(\))([0-9])/'), '$1*$2', $text);
			@eval('$val=' . $text . ';');
			if(isset($val))
			{
				return $text . '=' . $val;
			}
			else
			{
				return $this->autoText(ONRECEIVE_ERROR_MATH, $follower);
			}
		}

		// 数据库回复
		$sql =	' SELECT a.val FROM ' . $this->_prefix . 'question AS q ' .
				' JOIN ' . $this->_prefix . 'aq AS aq ON q.id = aq.q_id ' .
				' JOIN ( ' .
				' 	SELECT MAX(aq.`level`) AS ml FROM ' . $this->_prefix . 'question AS q ' .
				' 	JOIN ' . $this->_prefix . 'aq AS aq ON q.id = aq.q_id ' .
				' 	WHERE q.val = "' . $this->escape($text) . '" ' .
				' ) AS m ON m.ml = aq.`level` ' .
				' JOIN ' . $this->_prefix . 'answer AS a ON a.id = aq.a_id ' .
				' WHERE q.val = "' . $this->escape($text) . '" ';
		$val = $this->fetchCol($sql);
		if(!empty($val) && is_array($val))
		{
			shuffle($val);
			return current($val);
		}

		// 学习判断
		$temp = explode("\n", $text);
		if(count($temp) == 2)
		{
			$text = array(trim($temp[0]), trim($temp[1]));
			if(preg_match('/^问题.+/', $text[0]) && preg_match('/^回答.+/', $text[1]))
			{
				$text[0] = substr($text[0], 6);
				$text[1] = substr($text[1], 6);
				$learned = $this->addAnswer($text[0], $text[1], $follower['level']);
				if($learned)
				{
					return $this->autoText(ONRECEIVE_LEARNED, $follower);
				}
			}
		}
		// Simsimi
		Factory::loadLibrary('curlhelper');
		$simconf = $GLOBALS['CONFIG']['SIMSIMI'];
		$curlhelper = new CurlHelper($simconf['CURL']);
		$response = $curlhelper->request($simconf['API'] . $text, array());
		$response = json_decode($response['body'], true);
		if($response['result'] == 100)
		{
			$this->addAnswer($text, $response['response'], $simconf['LEVEL']);
			return $response['response'];
		}

		// 请求调教
		return $this->autoText(ONRECEIVE_UNLEARNED, $follower);
	}
}
