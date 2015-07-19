<?php
global $enabled_modules;

// pretect it from non-console access
if (PHP_SAPI != 'cli') {
  die();
}

require_once('bootstrap.php');

$command = $argv[1];
$arg1 = isset($argv[2]) ? $argv[2] : null;
$arg2 = isset($argv[3]) ? $argv[3] : null;
$arg3 = isset($argv[4]) ? $argv[4] : null;
$arg4 = isset($argv[5]) ? $argv[5] : null;
$arg5 = isset($argv[6]) ? $argv[6] : null;
$arg6 = isset($argv[7]) ? $argv[7] : null;

$settings = Vars::getSettings();

foreach ($enabled_modules as $module) {
  $module_console_file = MODULESROOT . DS . $module . DS . 'console.php';
  if (is_file($module_console_file)) {
    include_once $module_console_file;
  }
}