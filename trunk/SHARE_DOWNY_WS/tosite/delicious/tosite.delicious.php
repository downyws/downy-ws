<?php
#- Delicious
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteDelicious extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('delicious');
	}

	public function getUrl($params)
	{
		$url = 'http://www.delicious.com/save' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) .
				'&jump=yes';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
