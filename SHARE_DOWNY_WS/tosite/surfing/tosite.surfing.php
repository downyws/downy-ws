<?php
#- 翼分享
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteSurfing extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('surfing');
	}

	public function getUrl($params)
	{
		$url = 'http://s.189share.com/interface.jsp' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		return $url;
	}
}
