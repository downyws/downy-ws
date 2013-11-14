<?php
#- QQ书签
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteQQShuqian extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('qqshuqian');
	}

	public function getUrl($params)
	{
		$url = 'http://shuqian.qq.com/post' . 
				'?from=' .
				'&uri=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		return $url;
	}
}
