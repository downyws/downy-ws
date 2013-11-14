<?php
#- 139说客
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteS139 extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('s139');
	}

	public function getUrl($params)
	{
		$url = 'http://shequ.10086.cn/share/share.php' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		return $url;
	}
}
