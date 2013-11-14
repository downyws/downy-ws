<?php
#- Twitter
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteTwitter extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('twitter');
	}

	public function getUrl($params)
	{
		$url = 'https://twitter.com/intent/tweet' .
				'?text=' . urlencode($params['url']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
