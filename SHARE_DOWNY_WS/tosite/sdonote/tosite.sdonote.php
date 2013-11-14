<?php
#- 麦库记事
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteSdoNote extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('sdonote');
	}

	public function getUrl($params)
	{
		$url = 'http://note.sdo.com/tool/collect' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) . 
				'&text=&from=baidu';
		if(count($params['img']) > 0)
		{
			$url .= '&images=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
