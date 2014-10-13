<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');
define('APP_DIR_APPS', APP_DIR . 'apps/');

include_once(APP_DIR . 'config.php');
include_once(APP_DIR . 'global.php');

include_once('../../framework/framework.core.php');

include_once(APP_DIR_PLUGS . 'plugs.sitesetting.php');
Front::dispatch();
