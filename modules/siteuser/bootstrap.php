<?php
define('SITEUSER_FORM_SPAM_TOKEN', 'siteuser form spam token');

$user = User::getInstance();
if (!is_cli() && $user->isLogin() && is_backend()) {
  
  // register admin
  Backend::registerSideNav(
  '
  <li>
    <a href="#"><i class="fa fa-users"></i> '.i18n(array('en' => 'User', 'zh' => '用户')).'<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
      <li><a href="'.uri('admin/user/list').'">'.i18n(array('en' => 'Manage user', 'zh' => '管理用户')).'</a></li>
      <li><a href="'.uri('admin/permission/manage').'">'.i18n(array('en' => 'Manage permission', 'zh' => '管理权限')).'</a></li>
    </ul>
  </li>
  '        
  );
}