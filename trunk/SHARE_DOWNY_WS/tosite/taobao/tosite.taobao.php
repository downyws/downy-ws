<?php
#- 我的淘宝
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteTaobao extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('taobao');
	}

	public function getUrl($params)
	{
		$url = 'http://share.jianghu.taobao.com/share/addShare.htm' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		return $url;
	}
}
