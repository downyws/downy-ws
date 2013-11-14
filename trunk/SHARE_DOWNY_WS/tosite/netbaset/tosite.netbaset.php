<?php
#- 网易微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteNetbaseT extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('netbaset');
	}

	public function getUrl($params)
	{
		$url = 'http://t.163.com/article/user/checkLogin.do' . 
				'?info=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) .
				'&source=' . urlencode($params['url']);
		if(count($params['img']) > 0)
		{
			$url .= '&images=' . urlencode(current($params['img'])) .
					'&togImg=true';
		}
		return $url;
	}
}
