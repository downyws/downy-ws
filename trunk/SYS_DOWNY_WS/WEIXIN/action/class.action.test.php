<?php
class ActionTest extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		if(!$_POST)
		{
			$params = [
				'type' => 'weixin',
				'url' => WEIXIN_URL, 
				'content' => '', 
				'menu' => '',
				'encode' => 'utf8'
			];
		}
		else
		{
			$params = $this->_submit->obtain($_REQUEST, [
				'type' => [['valid', 'in', '测试类型错误', null, ['weixin', 'simsimi']]],
				'url' => [['valid', 'url', '请求地址错误',  null, null]],
				'content' => [['format', 'trim']],
				'menu' => [['format', 'trim']],
				'gbk' => [['format', 'int']],
				'follow' => [['format', 'trim']],
				'send' => [['format', 'trim']]
			]);

			if(isset($params['type']) && $params['type'] == 'simsimi')
			{
				Factory::loadLibrary('curlhelper');
				$simconf = $GLOBALS['CONFIG']['SIMSIMI'];
				$curlhelper = new CurlHelper($simconf['CURL']);
				$response = $curlhelper->request($simconf['API'] . $params['content'], []);
				$response = var_export($response['body'], true);
				$this->assign('response', $response);
			}
			else if(!empty($this->_submit->errors))
			{
				$response = var_export($this->_submit->errors, true);
			}
			else
			{
				$setting = ['to' => 'toUser', 'from' => str_replace(' ', '_', APP_NAME), 'time' => time()];
				// 关注
				if(!empty($params['follow']))
				{
					$data = '<xml>' .
								'<ToUserName><![CDATA[%s]]></ToUserName>' .
								'<FromUserName><![CDATA[%s]]></FromUserName>' .
								'<CreateTime>%s</CreateTime>' .
								'<MsgType><![CDATA[event]]></MsgType>' .
								'<Event><![CDATA[subscribe]]></Event>' .
							'</xml>';
					$data = sprintf($data, $setting['to'], $setting['from'], $setting['time']);
				}
				// 点击菜单
				else if(!empty($params['menu']))
				{
					$data = '<xml>' .
								'<ToUserName><![CDATA[%s]]></ToUserName>' .
								'<FromUserName><![CDATA[%s]]></FromUserName>' .
								'<CreateTime>%s</CreateTime>' .
								'<MsgType><![CDATA[event]]></MsgType>' .
								'<Event><![CDATA[CLICK]]></Event>' .
								'<EventKey><![CDATA[%s]]></EventKey>' .
								'<FuncFlag>0</FuncFlag>' .
							'</xml>';
					$data = sprintf($data, $setting['to'], $setting['from'], $setting['time'], $params['menu']);
				}
				// 发送内容
				else
				{
					$data = '<xml>' .
								'<ToUserName><![CDATA[%s]]></ToUserName>' .
								'<FromUserName><![CDATA[%s]]></FromUserName>' .
								'<CreateTime>%s</CreateTime>' .
								'<MsgType><![CDATA[text]]></MsgType>' .
								'<Content><![CDATA[%s]]></Content>' .
								'<FuncFlag>0</FuncFlag>' .
							'</xml>';
					$data = sprintf($data, $setting['to'], $setting['from'], $setting['time'], $params['content']);
				}

				$nonce = rand(10000, 99999);
				$sign = [WEIXIN_TOKEN, '' . $setting['time'], '' . $nonce];
				sort($sign);
				$sign = implode($sign);
				$url = $params['url'] . ((stripos($params['url'], '?') === false) ? '?' : '&') . 
					'timestamp=' . $setting['time'] . '&nonce=' . $nonce . '&signature=' . sha1($sign);
				$options = [
					'http' => [
						'header' => 'Content-type: raw/xml\r\nUser-Agent: Mozilla/5.0',
						'method' => 'POST',
						'content' => $data,
					],
				];
				$context = stream_context_create($options);
				$response = file_get_contents($url, false, $context);
				if($params['gbk'])
				{
					$s = @iconv("gb2312", "utf-8", $response);
					if($s)
					{
						$response = $s;
					}
					else
					{
						$response = "iconv gbk to utf8 failed.\r\n\r\n" . $response;
					}
				}
			}

			$this->assign('response', $response);
		}

		$this->assign('params', $params);
	}
}
