<?php
$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => '404 Page not found',
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');
$html->renderOut('core/backend/404', array(), true);
$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;


