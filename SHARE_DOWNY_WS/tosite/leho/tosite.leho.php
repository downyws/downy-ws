<?php
#- 爱乐活
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteLeho extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('leho');
	}

	public function getUrl($params)
	{
		$url = 'http://i.leho.com/api/share' .
				'?url=' . urlencode($params['url']) .
				'&poptitle=' . urlencode($params['title']) .
				'&content=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
