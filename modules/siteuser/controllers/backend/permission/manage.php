<?php

// handle submission
if (isset($_POST['submit'])) {
  SitePermissionRole::truncate();
  foreach ($_POST as $key => $val) {
    if (strstr($key, 'role_')) {
      $tokens = explode('_', $key);
      $role_id = (int)$tokens[1];
      foreach ($val as $permission_id => $v) {
        $spr = new SitePermissionRole();
        $spr->setRoleId($role_id);
        $spr->setPermissionId($permission_id);
        $spr->save();
      }
    }
  }
  Message::register(new Message(Message::SUCCESS, 'Permissions updated successfully!'));
  HTML::forwardBackToReferer();
}

$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Manage permission', 'zh' => '管理权限')),
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');

$html->renderOut('siteuser/backend/permission/manage', array(
    'permissions' => SitePermission::findAll(),
    'roles' => SiteRole::findAll()
), true);


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

