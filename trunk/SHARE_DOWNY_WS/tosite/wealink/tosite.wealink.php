<?php
#- 若邻网
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteWealink extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('wealink');
	}

	public function getUrl($params)
	{
		$url = 'http://share.wealink.com/share/add/' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) .
				'&content=' . urlencode($params['desc']);
		return $url;
	}
}
