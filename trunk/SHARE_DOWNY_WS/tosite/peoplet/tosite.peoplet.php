<?php
#- 人民微博
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSitePeopleT extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('peoplet');
	}

	public function getUrl($params)
	{
		$url = 'http://t.people.com.cn/bbsShare.action' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['title']) . ' ' . urlencode($params['desc']) . 
				'&showtype=&bigtype=&appkey=&site=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
