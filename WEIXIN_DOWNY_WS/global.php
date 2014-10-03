<?php

define('APP_NAME', 'Downy Weixin');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

// 关注者状态
define('FOLLOWER_STATE_NORMAL', 1);	// 添加关注
define('FOLLOWER_STATE_CANCEL', 2);	// 取消关注

// 事件回复定义
define('ONEVENT_SUBSCRIBE', '_DOWNY_SUBSCRIBE');		// 添加关注
define('ONEVENT_UNSUBSCRIBE', '_DOWNY_UNSUBSCRIBE');	// 取消关注

// 未实现的类型回复
define('ONRECEIVE_IMAGE', '_DOWNY_UNSUPPORTED_IMAGE');			// 图片
define('ONRECEIVE_VOICE', '_DOWNY_UNSUPPORTED_VOICE');			// 语音
define('ONRECEIVE_VIDEO', '_DOWNY_UNSUPPORTED_VIDEO');			// 视频
define('ONRECEIVE_LOCATION', '_DOWNY_UNSUPPORTED_LOCATION');	// 地理位置
define('ONRECEIVE_LINK', '_DOWNY_UNSUPPORTED_LINK');			// 链接

// 其他
define('ONRECEIVE_ERROR_MATH', '_DOWNY_ERROR_MATH');	// 数学公式错
define('ONRECEIVE_UNLEARNED', '_DOWNY_UNLEARNED');		// 未学会
define('ONRECEIVE_LEARNED', '_DOWNY_LEARNED');			// 教会了
define('ONEDIT_NICKNAME_FAILED', '_DOWNY_NICKNAME_FAILED');		// 编辑昵称失败
define('ONEDIT_NICKNAME_SUCCESS', '_DOWNY_NICKNAME_SUCCESS');	// 编辑昵称成功

// 等级达到多少后可以免审核
define('NOT_NEED_AUDIT_LEVEL', 25);

// Simsimi
$GLOBALS['CONFIG']['SIMSIMI'] = [
	'LEVEL' => 5,
	'API' => 'http://api.simsimi.com/request.p?lc=ch&ft=0.0&key=' . SIMSIMI_KEY . '&text=',
	// http://sandbox.api.simsimi.com/request.p?lc=ch&ft=0.0&key=your_paid_key&text=内容
	// http://api.simsimi.com/request.p?lc=ch&ft=0.0&key=your_paid_key&text=内容
	'CURL' => [
		'TIMEOUT' => 3,
		'ENCODING' => 'gzip, deflate',
		'PROXY' => false, 
		'PROXYPORT' => '',
		'COOKIE' => ['OPEN' => false, 'LOCK' => false, 'PATH' => ''],
		'REFERER' => ['OPEN' => false, 'LOCK' => false, 'VALUE' => ''],
		'USERAGENT' => ['OPEN' => false, 'VALUE' => '0'],
		'AUTO_REDIRECT_COUNT' => 1
	]
];
