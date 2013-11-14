<?php
#- 51游戏社区
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteS51 extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('s51');
	}

	public function getUrl($params)
	{
		$url = 'http://share.51.com/share/share.php' . 
				'?vaddr=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) . 
				'&type=8';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
