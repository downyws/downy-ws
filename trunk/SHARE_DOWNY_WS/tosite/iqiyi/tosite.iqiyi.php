<?php
#- 奇艺奇谈
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteIqiyi extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('iqiyi');
	}

	public function getUrl($params)
	{
		$url = 'http://t.qiyi.com/share/share.php' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		return $url;
	}
}
