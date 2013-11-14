<?php
#- 点点网
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteDiandian extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('diandian');
	}

	public function getUrl($params)
	{
		$url = 'http://www.diandian.com/share' . 
				'?lo=' . urlencode($params['url']) . 
				'&ti=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
				'&type=link';
		return $url;
	}
}
