<?php
#- 豆瓣网
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteDouban extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('douban');
	}

	public function getUrl($params)
	{
		$url = 'http://shuo.douban.com/!service/share' . 
				'?href=' . urlencode($params['url']) . 
				'&name=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&image=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
