<?php
#- 百度空间
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteBaiduHi extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('baiduhi');
	}

	public function getUrl($params)
	{
		$url = 'http://hi.baidu.com/pub/show/share' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) .
				'&content=&linkid=';
		return $url;
	}
}
