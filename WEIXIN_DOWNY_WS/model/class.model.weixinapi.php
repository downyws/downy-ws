<?php
class ModelWeixinApi extends Model
{
	public $_table = '';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
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
		else
		{
			$follower = array(
				'openid' => $openid,
				'level' => 0,
				'state' => FOLLOWER_STATE_NORMAL
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
		switch($request->MsgType)
		{
			case 'event':
				switch($request->Event)
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
			case 'text':
				// »Ø¸´
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
		return 'coding...' . '[' . $text . ']';
		// Coding...
	}
}
