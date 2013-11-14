<?php
#- 新华微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteNewshome extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('newshome');
	}

	public function getUrl($params)
	{
		$url = 'http://t.home.news.cn/share.jsp' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
