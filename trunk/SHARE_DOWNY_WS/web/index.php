<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');
define('APP_DIR_TOSITE', APP_DIR . 'tosite/');

include_once('../../config/config.share.php');
include_once(APP_DIR . 'global.php');

include_once('../../framework/framework.core.php');

include_once(SCRIPT_DIR . 'plug.sitesetting.child.php');
Front::dispatch();
