<?php

/**
 * This script makes up shell scripts that calls phantomjs
 */

require __DIR__ . "/../../../bootstrap.php";

// prepare directories
$script_dir = CACHE_DIR . DS . 'phantomjs_scripts';
$result_dir = CACHE_DIR . DS . 'phantomjs_results';
if (!is_dir($script_dir)) {
  mkdir($script_dir);
}
if (!is_dir($result_dir)) {
  mkdir($result_dir);
}

// generates phantomjs scripts
$accounts = WechatAccount::findAllToCrawl(3500);
foreach ($accounts as $account) {
  
  // append Shanghai timestamp at the end of file name, so that when we collect
  // the file, we know when it was created
  $original_timezone = date_default_timezone_get();
  date_default_timezone_set('Asia/Shanghai');
  $filename = 'account_crawl_' . $account->getWechatId() . '_' . date('Y-m-d-H-i-s');
  date_default_timezone_set($original_timezone);
  
  
  $script_file = $script_dir . DS . $filename . '.sh';
  $result_file = $result_dir . DS . $filename;
  file_put_contents(
          $script_file, 
          'phantomjs ' . MODULESROOT . '/wechat_account/phantomjs/articles.js "http://weixin.sogou.com/gzh?openid=' . $account->getOpenid() . '" > ' . $result_file
  );
  chmod($script_file, 0700);
  
  $account->setLastScheduled(time());
  $account->save();
}

