<?php
#- 花瓣
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteHuaban extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('huaban');
	}

	public function getUrl($params)
	{
		$url = 'http://huaban.com/bookmarklet/' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&media=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
