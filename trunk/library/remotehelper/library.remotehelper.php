<?php
class RemoteHelper
{
    public function getDeviceType()
    {
		if(stripos($_SERVER['HTTP_USER_AGENT'], 'spider') !== false)
		{
			return 'SPIDER'; // 爬虫
		}
		else if(stripos($_SERVER['HTTP_USER_AGENT'], 'ipad') !== false)
		{
			return 'PAD'; // 平板
		}
		else if(stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') !== false)
		{
			return 'PHONE'; // 手机
		}
		else if(stripos($_SERVER['HTTP_USER_AGENT'], 'android') !== false)
		{
			if(!empty($_SERVER['HTTP_X_WAP_PROFILE']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			{
				return 'PHONE'; // 手机
			}
			else
			{
				return 'PAD'; // 平板
			}
		}
		else
		{
			return 'PC'; // 电脑
		}
		return 'UNKNOW'; // 未知
    }
}
