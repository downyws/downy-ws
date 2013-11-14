<?php
#- 饭否
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteFanfou extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('fanfou');
	}

	public function getUrl($params)
	{
		$url = 'http://fanfou.com/sharer' . 
				'?u=' . urlencode($params['url']) . 
				'&t=' . urlencode($params['desc']);
		return $url;
	}
}
