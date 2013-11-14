<?php
#- 搜狐微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteTSohu extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('tsohu');
	}

	public function getUrl($params)
	{
		$url = 'http://t.sohu.com/third/post.jsp' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
