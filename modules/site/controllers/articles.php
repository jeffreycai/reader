<?php

require_permission('manager feeds');


$html = new HTML();
$html->renderOut('site/html_header', array(
    'title' => '订阅文章 :: ' . $settings['sitename'],
    'body_class' => 'articles'
));
$html->renderOut('site/header/subscription');
$html->renderOut('site/nav/main');
$html->renderOut('site/articles');
$html->renderOut('site/footer');
$html->renderOut('site/html_footer');

