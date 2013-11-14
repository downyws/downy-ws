<?php
#- Facebook
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteFacebook extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('facebook');
	}

	public function getUrl($params)
	{
		$url = 'http://www.facebook.com/share.php' . 
				'?u=' . urlencode($params['url']) . 
				'&t=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
