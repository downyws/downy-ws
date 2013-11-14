<?php
#- Myspace
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteMyspace extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('myspace');
	}

	public function getUrl($params)
	{
		$url = 'http://www.myspace.com/share' . 
				'?u=' . urlencode($params['url']) . 
				'&t=' . urlencode($params['desc']) . 
				'&tc=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
