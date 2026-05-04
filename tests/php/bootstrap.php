<?php
declare(strict_types=1);

$loader = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (is_readable($loader)) {
    require $loader;
}

define('IN_PHPLOGCON', true);
$gl_root_path = dirname(__DIR__, 2) . '/src/';

require_once $gl_root_path . 'include/constants_general.php';
require_once $gl_root_path . 'include/constants_errors.php';
require_once $gl_root_path . 'include/constants_logstream.php';
require_once $gl_root_path . 'include/constants_filters.php';
