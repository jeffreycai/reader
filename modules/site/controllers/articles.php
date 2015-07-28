<?php

require_permission('manager feeds');

// get vars from $_GET
$page = isset($_GET['page']) && !empty($_GET['page']) ? intval(strip_tags($_GET['page'])) : 1;
$category = isset($_GET['category']) && !empty($_GET['category']) ? strip_tags($_GET['category']) : null;
$unread = isset($_GET['unread']) && !empty($_GET['unread']) ? strip_tags($_GET['unread']) : null;

$html = new HTML();
$html->renderOut('site/html_header', array(
    'title' => '订阅文章 :: ' . $settings['sitename'],
    'body_class' => 'articles'
));
$html->renderOut('site/header/subscription', array(
    'unread' => $unread,
    'category' => $category
));
$html->renderOut('site/nav/main');
$html->renderOut('site/articles', array(
    'page' => $page ? $page : 1,
    'category' => $category,
    'unread' => $unread
));
$html->renderOut('site/footer');
$html->renderOut('site/html_footer');

