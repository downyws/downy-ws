<?php
#- QQ好友
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteQQIM extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('qqim');
	}

	public function getUrl($params)
	{
		$url = 'http://connect.qq.com/widget/shareqq/index.html' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . 
				'&desc=' . urlencode($params['desc']) .
				'&summary=&site=baidu';
		if(count($params['img']) > 0)
		{
			$url .= '&pics=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
