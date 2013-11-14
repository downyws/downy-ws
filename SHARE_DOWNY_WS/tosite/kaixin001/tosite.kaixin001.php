<?php
#- 开心网
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteKaixin001 extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('kaixin001');
	}

	public function getUrl($params)
	{
		$url = 'http://www.kaixin001.com/repaste/share.php' .
				'?rurl=' . urlencode($params['url']) . 
				'&rtitle=' . urlencode($params['title']) . 
				'&rcontent=' . urlencode($params['desc']);
		return $url;
	}
}
