<?php

define('APP_NAME', 'Downy Weixin');

define('APP_TIMEZONE', 'Asia/Shanghai');

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

// ��ע��״̬
define('FOLLOWER_STATE_NORMAL', 1);	// ��ӹ�ע
define('FOLLOWER_STATE_CANCEL', 2);	// ȡ����ע

// �¼��ظ�����
define('ONEVENT_SUBSCRIBE', '_DOWNY_SUBSCRIBE');		// ��ӹ�ע
define('ONEVENT_UNSUBSCRIBE', '_DOWNY_UNSUBSCRIBE');	// ȡ����ע

// δʵ�ֵ����ͻظ�
define('ONRECEIVE_IMAGE', '_DOWNY_UNSUPPORTED_IMAGE');			// ͼƬ
define('ONRECEIVE_VOICE', '_DOWNY_UNSUPPORTED_VOICE');			// ����
define('ONRECEIVE_VIDEO', '_DOWNY_UNSUPPORTED_VIDEO');			// ��Ƶ
define('ONRECEIVE_LOCATION', '_DOWNY_UNSUPPORTED_LOCATION');	// ����λ��
define('ONRECEIVE_LINK', '_DOWNY_UNSUPPORTED_LINK');			// ����

// ����
define('ONRECEIVE_ERROR_MATH', '_DOWNY_ERROR_MATH');	// ��ѧ��ʽ��
define('ONRECEIVE_UNLEARNED', '_DOWNY_UNLEARNED');		// δѧ��
define('ONRECEIVE_LEARNED', '_DOWNY_LEARNED');			// �̻���