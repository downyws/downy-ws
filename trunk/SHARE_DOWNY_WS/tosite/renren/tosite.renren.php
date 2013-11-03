<?php
#- 人人网
#- 1.001
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteRenren extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('renren');
	}

	public function getUrl($params)
	{
		// coding
		return '#renren';
	}
}
