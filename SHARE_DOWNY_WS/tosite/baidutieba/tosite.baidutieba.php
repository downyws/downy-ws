<?php
#- 百度帖吧
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteBaiduTieba extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('baidutieba');
	}

	public function getUrl($params)
	{
		$url = 'http://tieba.baidu.com/f/commit/share/openShareApi' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) .
				'&desc=' . urlencode($params['desc']) .
				'&comment=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
