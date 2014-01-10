<?php

define('APP_NAME', 'Downy Weixin');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

// 关注者状态
define('FOLLOWER_STATE_NORMAL', 1);	// 添加关注
define('FOLLOWER_STATE_CANCEL', 2);	// 取消关注

// 事件回复定义
define('ONEVENT_SUBSCRIBE', '_DOWNY_SUBSCRIBE';		// 添加关注
define('ONEVENT_UNSUBSCRIBE', '_DOWNY_UNSUBSCRIBE';	// 取消关注
