<?php
#- 摇篮空间
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteYaolan extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('yaolan');
	}

	public function getUrl($params)
	{
		$url = 'http://space.yaolan.com/share/myshare/' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']) . 
				'&desc=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
