<?php
#- 和讯微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteHexunT extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('hexunt');
	}

	public function getUrl($params)
	{
		$url = 'http://t.hexun.com/channel/shareweb.aspx' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		return $url;
	}
}
