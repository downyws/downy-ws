<?php
#- 微博
#- 0.5
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteWeibo extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('weibo');
	}

	public function getUrl($params)
	{
		// coding
		return '#weibo';
	}
}
