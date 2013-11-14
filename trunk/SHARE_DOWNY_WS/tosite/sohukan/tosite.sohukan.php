<?php
#- 搜狐随身看
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteSohuKan extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('sohukan');
	}

	public function getUrl($params)
	{
		$url = 'http://kan.sohu.com/share/' . 
				'?href=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
				'&appkey=';
		return $url;
	}
}
