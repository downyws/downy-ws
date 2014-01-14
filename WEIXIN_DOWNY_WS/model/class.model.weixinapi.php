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
			$data = array('val' => $question, 'is_adjust' => $level >= NOT_NEED_AUDIT_LEVEL ? 1 : 0);
			$q_id = $this->insert($data, 'question');
		}

		$condition = array();
		$condition[] = array('val' => array('eq', $answer));
		$a_id = $this->getOne($condition, 'id', 'answer');
		if(!$a_id)
		{
			$data = array('val' => $answer, 'msg_type' => 'text');
			$a_id = $this->insert($data, 'answer');
		}

		if($q_id && $a_id)
		{
			$data = array('q_id' => $q_id, 'a_id' => $a_id, 'level' => $level, 'is_adjust' => $level >= NOT_NEED_AUDIT_LEVEL ? 1 : 0);
			$this->insert($data, 'aq', true);
			if($level < NOT_NEED_AUDIT_LEVEL)
			{
				$condition = array();
				$condition[] = array('id' => array('eq', $q_id));
				$data = array('is_adjust' => 0);
				$this->update($condition, $data, 'question');
			}
			return true;
		}
		return false;
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
				return array('text', $text . '=' . $val);
			}
			else
			{
				return $this->autoText(ONRECEIVE_ERROR_MATH, $follower);
			}
		}

		// 昵称修改
		if(preg_match('/^我的名字叫.+/', $text))
		{
			$text = substr($text, 15);
			$learned = $this->editNickname($text, $follower);
			if($learned)
			{
				return $this->autoText(ONEDIT_NICKNAME_SUCCESS, $follower);
			}
			else
			{
				return $this->autoText(ONEDIT_NICKNAME_FAILED, $follower);
			}
		}

		// 数据库回复
		$sql =	' SELECT a.val, a.msg_type FROM ' . $this->_prefix . 'question AS q ' .
				' JOIN ' . $this->_prefix . 'aq AS aq ON q.id = aq.q_id ' .
				' JOIN ( ' .
				' 	SELECT MAX(aq.`level`) AS ml FROM ' . $this->_prefix . 'question AS q ' .
				' 	JOIN ' . $this->_prefix . 'aq AS aq ON q.id = aq.q_id ' .
				' 	WHERE q.val = "' . $this->escape($text) . '" ' .
				' ) AS m ON m.ml = aq.`level` ' .
				' JOIN ' . $this->_prefix . 'answer AS a ON a.id = aq.a_id ' .
				' WHERE q.val = "' . $this->escape($text) . '" ';
		$val = $this->fetchRows($sql);
		if(!empty($val) && is_array($val))
		{
			shuffle($val);
			$val = current($val);
			return array($val['msg_type'], $val['val']);
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
			return array('text', $response['response']);
		}

		// 请求调教
		return $this->autoText(ONRECEIVE_UNLEARNED, $follower);
	}

	public function cancelFollow($openid)
	{
		$condition = array();
		$condition[] = array('openid' => array('eq', $openid));
		$data = array('state' => FOLLOWER_STATE_CANCEL);
		$this->update($condition, $data, 'follower');
	}

	public function editNickname($nickname, $fofollower)
	{
		if(preg_match('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\<\>\,\.\/\?\'\"\:\;\[\]\{\}\\\|]/', $nickname))
		{
			return false;
		}
		$condition = array();
		$condition[] = array('id' => array('eq', $fofollower['id']));
		$data = array('nickname' => $nickname);
		return $this->update($condition, $data, 'follower');
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

	public function getNickname($openid)
	{
		$condition = array();
		$condition[] = array('openid' => array('eq', $openid));
		$follower = $this->getObject($condition, array(), 'follower');
		if(!empty($follower) && $follower['nickname'] != '')
		{
			return $follower['nickname'];
		}
		return $openid;
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
						list($response['msgType'], $response['content']) = $this->autoText(ONEVENT_SUBSCRIBE, $follower);
						break;
					case 'unsubscribe':
						list($response['msgType'], $response['content']) = $this->autoText(ONEVENT_UNSUBSCRIBE, $follower);
						$this->cancelFollow($data->FromUserName);
						break;
				}
				break;
			case 'image':
				list($response['msgType'], $response['content']) = $this->autoText(ONRECEIVE_IMAGE, $follower);
				break;
			case 'voice':
				list($response['msgType'], $response['content']) = $this->autoText(ONRECEIVE_VOICE, $follower);
				break;
			case 'video':
				list($response['msgType'], $response['content']) = $this->autoText(ONRECEIVE_VIDEO, $follower);
				break;
			case 'location':
				list($response['msgType'], $response['content']) = $this->autoText(ONRECEIVE_LOCATION, $follower);
				break;
			case 'link':
				list($response['msgType'], $response['content']) = $this->autoText(ONRECEIVE_LINK, $follower);
				break;
			case 'text':
				list($response['msgType'], $response['content']) = $this->autoText($data->Content, $follower);
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
}
