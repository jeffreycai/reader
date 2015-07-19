<?php
// define global const
define('UID_BACKEND_LOGIN_FORM', 'Backend Login Form');


define('FILE_DIR', WEBROOT . DS . 'files');
if (!is_dir(FILE_DIR)) {
  mkdir(FILE_DIR);
}
if (!is_writable(FILE_DIR)) {
  die('File directory needs to be writable');
}

define('CACHE_DIR', FILE_DIR . DS . 'cache');
if (!is_dir(CACHE_DIR)) {
  mkdir(CACHE_DIR);
}
if (!is_writable(CACHE_DIR)) {
  die('Cache directory needs to be writable');
}



// register maintenance admin page
$user = User::getInstance();
if (!is_cli() && $user->isLogin() && is_backend()) {
  
  // register admin
  Backend::registerSideNav(
  '
  <li>
    <a href="'.uri('admin/maintenance').'"><i class="fa fa-wrench"></i> '.i18n(array('en' => 'Maintenance', 'zh' => '系统维护')) . '</a>
  </li>
  '        
  );

}