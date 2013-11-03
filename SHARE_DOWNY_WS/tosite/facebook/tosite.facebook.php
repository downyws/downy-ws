<?php
#- Facebook
#- 1.1
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteFacebook extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('facebook');
	}

	public function getUrl($params)
	{
		// coding
		return '#facebook';
	}
}
