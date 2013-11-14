<?php
#- 腾讯朋友
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSitePengyou extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('pengyou');
	}

	public function getUrl($params)
	{
		$url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey' . 
				'?to=pengyou&summary=&desc=' .
				'&url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pics=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
