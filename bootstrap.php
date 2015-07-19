<?php
/** global constant **/
define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', __DIR__);
define('MODULESROOT', WEBROOT . DS . 'modules');

global $enabled_modules; // default enabled modules
global $custom_modules; // You can define your custom modules to be loaded
if (isset($custom_modules)) {
  $enabled_modules = $custom_modules;
} else {

  $enabled_modules = array(
      'core',
//      'static_cache',
      'database',
      'form',
      'site',
      'siteuser',
      'siteuser_profile',
      'wechat_account',
      'mail',
      'crawler',
      'log',
//      'siteuser_wechat_dummy'
  );

}

if (strpos(__DIR__, 'dev') !== false) {
  define('ENV', 'dev');
} else {
  //define('ENV', 'dev');
  define('ENV', 'prod');
}

// error reporting
error_reporting(E_DEPRECATED | E_USER_DEPRECATED | E_STRICT | E_ALL);

//-- include functions
//require(MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'functions.inc.php');
foreach ($enabled_modules  as $module) {
  $function_file = MODULESROOT . DS . $module . DS . 'includes' . DS . 'functions.inc.php';
  if (is_file($function_file)) {
    require $function_file;
  }
}

//-- auto load classes
//set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);
spl_autoload_register('custom_loader');

//-- session
session_start();

//-- initialize db if module required
if (in_array('database', $enabled_modules)) {
  include_once MODULESROOT . DS . 'database' . DS . 'bootstrap.php';
}

//-- get settings
global $settings;
$settings = Vars::getSettings();

//-- bootstrap modules
foreach ($enabled_modules as $module) {
  $module_bootstrap = MODULESROOT . DS . $module . DS . 'bootstrap.php';
  if (is_file($module_bootstrap)) {
    include_once $module_bootstrap;
  }
}

//-- default timezone
date_default_timezone_set('Australia/Sydney');


