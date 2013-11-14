<?php
#- 天涯社区
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteTianya extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('tianya');
	}

	public function getUrl($params)
	{
		$url = 'http://open.tianya.cn/widget/send_for.php' . 
				'?action=send-html&shareTo=1&relateTYUserName=&flashVideoUrl=' .
				'&url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&picUrl=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
