<?php
#- 搜狐白社会
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteSohuBai extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('sohubai');
	}

	public function getUrl($params)
	{
		$url = 'http://bai.sohu.com/share/blank/add.do' . 
				'?link=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		return $url;
	}
}
