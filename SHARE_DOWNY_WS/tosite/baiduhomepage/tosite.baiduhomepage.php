<?php
#- 百度首页
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteBaiduHomepage extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('baiduhomepage');
	}

	public function getUrl($params)
	{
		$url = 'http://www.baidu.com/home/page/show/url' . 
				'?url=' . urlencode($params['url']) . 
				'&name=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) .
				'&key=&apiType=&buttonType=&from=';
		return $url;
	}
}
