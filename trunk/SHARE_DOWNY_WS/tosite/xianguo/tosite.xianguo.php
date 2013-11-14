<?php
#- 鲜果
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteXianguo extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('xianguo');
	}

	public function getUrl($params)
	{
		$url = 'http://xianguo.com/service/submitdigg/' . 
				'?link=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
