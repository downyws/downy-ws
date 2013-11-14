<?php
#- 凤凰微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteIfengT extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('ifengt');
	}

	public function getUrl($params)
	{
		$url = 'http://t.ifeng.com/interface.php' . 
				'?sourceUrl=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) . 
				'&_c=share&_a=share';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
