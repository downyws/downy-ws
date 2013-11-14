<?php
#- QQ空间
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteQzone extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('qzone');
	}

	public function getUrl($params)
	{
		$url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey' .
				'?url=' . urlencode($params['url']) .
				'&title=' . urlencode($params['desc']) .
				'&desc=&summary=&site=';
		if(count($params['img']) > 0)
		{
			$url .= '&pics=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
