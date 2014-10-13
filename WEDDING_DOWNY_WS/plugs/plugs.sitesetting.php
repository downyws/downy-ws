<?php
// 参数处理
if(isset($_REQUEST['plugs_sitesetting']))
{
	$P_REQUEST = json_decode($_REQUEST['plugs_sitesetting'], true);
}
$P_REQUEST['S'] = isset($P_REQUEST['S']) ? strtoupper($P_REQUEST['S']) : '';

// 根级变量是否定义过
if(isset($_COOKIE['SITE_SETTING']))
{
	$SITE_SETTING = json_decode($_COOKIE['SITE_SETTING'], true);
}
if(!isset($SITE_SETTING) || !is_array($SITE_SETTING))
{
	$SITE_SETTING = [];
}
// 站点是否存在数据
if(!isset($SITE_SETTING[APP_NAME]) || !is_array($SITE_SETTING[APP_NAME]))
{
	$SITE_SETTING[APP_NAME] = [];
}
// 是否请求特定类型
if(in_array($P_REQUEST['S'], ['PC', 'MOBI']))
{
	$SITE_SETTING[APP_NAME]['TYPE'] = $P_REQUEST['S'];
}
// 检查类型是否正确
else if(!isset($SITE_SETTING[APP_NAME]['TYPE']) || !in_array($SITE_SETTING[APP_NAME]['TYPE'], ['MOBI', 'PC']))
{
	$SITE_SETTING[APP_NAME]['TYPE'] = in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) ? 'MOBI' : 'PC';
}
// 检查是否存在移动设备站点
$ONLY_PC = !file_exists(APP_DIR . 'web/mobi/');
if($ONLY_PC)
{
	$SITE_SETTING[APP_NAME]['TYPE'] = 'PC';
}
// 写回数据
$_COOKIE['SITE_SETTING'] = json_encode($SITE_SETTING);
setcookie('SITE_SETTING', $_COOKIE['SITE_SETTING'], time() + 86400000, '/', ROOT_DOMAIN);

// 当前站点类型
$TYPE_NOW = (stripos($_SERVER['PHP_SELF'], '/mobi/index.php') === 0) ? 'MOBI' : 'PC';

// 手持设备访问传统桌面版警告
if(in_array(REMOTE_DEVICE_TYPE, ['PAD', 'PHONE']) && $TYPE_NOW == 'PC' && $SITE_SETTING[APP_NAME]['TYPE'] == 'PC')
{
	if(!isset($SITE_SETTING[APP_NAME]['CLOSE_WARNING']) || $SITE_SETTING[APP_NAME]['CLOSE_WARNING'] < time())
	{
		Front::redirect(
			'http://' . ROOT_DOMAIN . '/mobi/index.php?a=message&m=pcsitewarning' .
			'&only_pc=' . ($ONLY_PC ? 1 : 0) . '&app_name=' . urlencode(APP_NAME) . '&callback=' . urlencode(REMOTE_REQUEST_URI)
		);
	}
}

// 当前站点与设置的站点类型不匹配
if($TYPE_NOW != $SITE_SETTING[APP_NAME]['TYPE'])
{
	$url = str_replace([APP_URL, APP_URL . 'mobi/'], APP_URL . ($SITE_SETTING[APP_NAME]['TYPE'] == 'MOBI' ? 'mobi/' : ''), REMOTE_REQUEST_URI);
	Front::redirect($url);
}
