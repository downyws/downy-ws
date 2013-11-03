<?php

define('FRAMEINFO_NAME', 'Downy Framework');
define('FRAMEINFO_VERSION', '4.1');
define('FRAMEINFO_AUTHOR', 'Wing075');
define('FRAMEINFO_AUTHOR_EMAIL', 'downyws@gmail.com');
define('FRAMEINFO_AUTHOR_DOMAIN', 'http://www.downy.ws/');

define('ROOT_DIR', dirname(dirname(__FILE__)) . '/');

define('FRAMEWORK_DIR',		dirname(__FILE__) . '/');
define('LIBRARY_DIR',		ROOT_DIR . 'library/');

define('APP_DIR_ACTION',	APP_DIR . 'action/');
define('APP_DIR_CACHE',		APP_DIR . 'cache/');
define('APP_DIR_LOGS',		APP_DIR . 'logs/');
define('APP_DIR_MODEL',		APP_DIR . 'model/');
define('APP_DIR_TEMPLATE',	APP_DIR . 'template/');

define('FRAMEWORLK_FILECACHE_EXPIRES', 3600);

define('APP_DOMAIN', $_SERVER['HTTP_HOST']);
define('APP_URL', (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . '/');
define('REMOTE_REQUEST_URI', (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('REMOTE_IP_ADDRESS', $_SERVER['REMOTE_ADDR']);

!defined('APP_TIMEZONE') && define('APP_TIMEZONE', 'Asia/Shanghai');
date_default_timezone_set(APP_TIMEZONE);

require_once(FRAMEWORK_DIR . '/framework.action.php');
require_once(FRAMEWORK_DIR . '/framework.db.php');
require_once(FRAMEWORK_DIR . '/framework.factory.php');
require_once(FRAMEWORK_DIR . '/framework.filecache.php');
require_once(FRAMEWORK_DIR . '/framework.front.php');
require_once(FRAMEWORK_DIR . '/framework.logs.php');
require_once(FRAMEWORK_DIR . '/framework.model.php');
require_once(FRAMEWORK_DIR . '/framework.submit.php');
