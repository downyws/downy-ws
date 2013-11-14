<?php
#- 堆糖
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteDuitang extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('duitang');
	}

	public function getUrl($params)
	{
		$url = 'http://www.duitang.com/collect/' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) . 
				'&desc=';
		if(count($params['img']) > 0)
		{
			$url .= '&img=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
