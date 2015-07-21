<?php
// dependency check
if (!module_enabled('crawler')) {
  die('Please enable crawler module');
}


$user = User::getInstance();
if (!is_cli() && $user->isLogin() && is_backend()) {
  
  // register admin
  Backend::registerSideNav(
  '
  <li>
    <a href="'.uri('admin/queue/list').'"><i class="fa fa-tasks"></i> '.i18n(array('en' => 'Queue', 'zh' => '队列')) . '</a>
  </li>
  '        
  );

}