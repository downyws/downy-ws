<?php
#- 轻笔记
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteQingbiji extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('qingbiji');
	}

	public function getUrl($params)
	{
		$url = 'http://www.qingbiji.cn/shareToQingBiJi' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . 
				'&content=' . urlencode($params['desc']) . 
				'&client_id=';
		if(count($params['img']) > 0)
		{
			$url .= '&pics=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
