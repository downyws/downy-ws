<?php
#- 蘑菇街
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteMogujie extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('mogujie');
	}

	public function getUrl($params)
	{
		$url = 'http://www.mogujie.com/mshare' . 
				'?url=' . urlencode($params['url']) . 
				'&content=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
