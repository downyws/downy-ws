<?php
#- 有道云笔记
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteYoudao extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('youdao');
	}

	public function getUrl($params)
	{
		$url = 'http://note.youdao.com/memory/' . 
				'?url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
				'&sumary=&product=';
		if(count($params['img']) > 0)
		{
			$url .= '&pic=' . urlencode(current($params['img']));
		}
		return $url;
	}
}
