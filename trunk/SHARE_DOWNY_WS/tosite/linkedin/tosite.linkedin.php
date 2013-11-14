<?php
#- Linkedin
#- 0
include_once(APP_DIR_TOSITE . 'base/tosite.base.php');

class ToSiteLinkedin extends ToSiteBase
{
	public function __construct()
	{
		parent::__construct('linkedin');
	}

	public function getUrl($params)
	{
		$url = 'http://www.linkedin.com/shareArticle' . 
				'?mini=true&ro=true&armin=armin&summary=' . 
				'&url=' . urlencode($params['url']) . 
				'&title=' . urlencode($params['desc']);
		return $url;
	}
}
