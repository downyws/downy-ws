<?php
#- 微信
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteWeixin extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('weixin');
	}

	public function getUrl($params)
	{
		// coding
		return '#weixin';
	}
}
