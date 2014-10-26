<?php

define('APP_DIR', dirname(dirname(dirname(__FILE__))) . '/');

$_GET['a'] = empty($_GET['a']) ? 'mobi_' : ('mobi_' . $_GET['a']);

include_once('../../../config/config.weixin.php');
include_once(APP_DIR . 'global.php');

include_once('../../../framework/framework.core.php');

include_once(SCRIPT_DIR . 'plug.sitesetting.child.php');
Front::dispatch();
