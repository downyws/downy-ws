<?php
define('APP_DIR', dirname(dirname(__FILE__)) . '/');

include_once('../../../config/config.sys.weixin.php');
include_once(APP_DIR . 'global.php');

include_once('../../../framework/framework.core.php');

include_once(SCRIPT_DIR . 'plug.access.back.php');
Front::dispatch();
