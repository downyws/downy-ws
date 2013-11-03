<?php
#- Twitter
#- 1
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteTwitter extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('twitter');
	}

	public function getUrl($params)
	{
		// coding
		return '#twitter';
	}
}
