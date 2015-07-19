<?php
$uid = $vars[1];
$user = SiteUser::findById($uid);
if (empty($user)) {
  HTML::forward('core/404');
}

require_once 'add_edit_submission.php';

$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Edit user', 'zh' => '编辑用户')),
), true);
$html->output('<div id="wrapper">');

$html->renderOut('core/backend/header');
$html->renderOut('siteuser/backend/user/edit', array(
    'user' => $user
));

$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

