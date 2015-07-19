<?php
$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => 'Dashboard',
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');
$html->renderOut('core/backend/dashboard', array(), true);
$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;


