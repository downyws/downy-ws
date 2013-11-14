<?php
#- 和讯转贴
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteHexunBookmark extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('hexunbookmark');
	}

	public function getUrl($params)
	{
		$url = 'http://bookmark.hexun.com/post.aspx' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		return $url;
	}
}
