<?php
$user = User::getInstance();
if (!is_cli() && $user->isLogin() && is_backend()) {
  
  // register admin
  Backend::registerSideNav(
  '
  <li>
    <a href="'.uri('admin/log/list').'"><i class="fa fa-book"></i> '.i18n(array('en' => 'System log', 'zh' => '系统日志')) . '</a>
  </li>
  '        
  );

}