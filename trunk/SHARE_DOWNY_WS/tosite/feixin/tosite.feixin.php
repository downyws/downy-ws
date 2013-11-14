<?php
#- 飞信
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteFeixin extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('feixin');
	}

	public function getUrl($params)
	{
		$url = 'http://space.feixin.10086.cn/api/cshare' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) .
				'&source=';
		return $url;
	}
}
