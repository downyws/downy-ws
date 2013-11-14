<?php
#- 人人网
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteRenren extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('renren');
	}

	public function getUrl($params)
	{
		$url = 'http://widget.renren.com/dialog/share' .
				'?resourceUrl=' . urlencode($params['url']) . 
				'&srcUrl=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) . 
				'&description=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
