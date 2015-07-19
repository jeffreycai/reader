<?php

require_permission('manager feeds');

$html = new HTML();
$html->renderOut('site/html_header', array(
    'title' => '公众号 :: ' . $settings['sitename'],
    'body_class' => 'accounts'
));
$html->renderOut('site/header/blank');
$html->renderOut('site/nav/main');
$html->renderOut('site/accounts', array(
    'accounts' => UserWechatAccount::findAll()
));
$html->renderOut('site/footer');
$html->renderOut('site/html_footer');

