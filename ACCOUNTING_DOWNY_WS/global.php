<?php

define('APP_NAME', 'Downy Accounting');

define('APP_TIMEZONE', 'Asia/Shanghai');

session_start();

$GLOBALS['CONFIG']['REMOTE_DEVICE_TYPE'] = true;

define('DEFAULT_SURPLUS_CURRENCY', 1);	// accounting_currency表的id

define('PAGE_SIZE', 20);
define('ADDRESS_SEARCH_SIZE', 10);
