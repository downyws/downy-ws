<?php

define('APP_DIR', dirname(dirname(dirname(__FILE__))) . '/');

$_GET['a'] = empty($_GET['a']) ? 'mobi_' : ('mobi_' . $_GET['a']);

include_once(APP_DIR . 'config.php');
include_once(APP_DIR . 'global.php');

include_once('../../../framework/framework.core.php');

Front::dispatch();
