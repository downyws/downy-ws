<?php
#- MSN
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteMSN extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('msn');
	}

	public function getUrl($params)
	{
		$url = 'http://profile.live.com/badge' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&screenshot=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
