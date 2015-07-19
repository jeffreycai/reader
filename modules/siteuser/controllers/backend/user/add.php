<?php

require_once 'add_edit_submission.php';

$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Add user', 'zh' => '添加用户')),
), true);
$html->output('<div id="wrapper">');

$html->renderOut('core/backend/header');
$html->renderOut('siteuser/backend/user/add');

$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

