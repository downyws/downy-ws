<?php
#- 百度收藏
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteBaiduCang extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('baiducang');
	}

	public function getUrl($params)
	{
		$url = 'http://cang.baidu.com/do/add' .
				'?iu=' . urlencode($params['url']) .
				'&it=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) .
				'&linkid=';
		return $url;
	}
}
