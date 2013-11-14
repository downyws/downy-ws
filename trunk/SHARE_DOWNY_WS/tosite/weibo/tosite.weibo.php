<?php
#- 微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteWeibo extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('weibo');
	}

	public function getUrl($params)
	{
		$url = 'http://service.weibo.com/share/share.php' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) .
				'&appkey=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
