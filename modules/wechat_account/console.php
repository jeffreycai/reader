<?php
require_once "console_db.php";

//-- Clear cache
if ($command == 'cc' && $arg1 == 'wechat') {
  echo " - clear cache folder 'phantomjs_scripts'\n";
  shell_exec('rm -rf ' . CACHE_DIR . '/phantomjs_scripts/*');
  echo " - clear cache folder 'phantomjs_results'\n";
  shell_exec('rm -rf ' . CACHE_DIR . '/phantomjs_results/*');
}