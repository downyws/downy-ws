<?php

define('APP_DIR', dirname(dirname(__FILE__)) . '/');

include_once(APP_DIR . 'config.php');
include_once(APP_DIR . 'global.php');

include_once('../../../framework/framework.core.php');

Front::dispatch();
