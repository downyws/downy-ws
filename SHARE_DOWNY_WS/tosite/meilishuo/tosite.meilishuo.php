<?php
#- 美丽说
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteMeilishuo extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('meilishuo');
	}

	public function getUrl($params)
	{
		$url = 'http://www.meilishuo.com/meilishuo_share' . 
				'?siteurl=' . urlencode($params['url']) . 
				'&content=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&picurl=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
