<?php

require_permission('manager feeds');

// get vars from $_GET
$page = isset($_GET['page']) && !empty($_GET['page']) ? intval(strip_tags($_GET['page'])) : 1;
$category = isset($_GET['cat']) && !empty($_GET['cat']) ? strip_tags($_GET['cat']) : null;
$read = isset($_GET['read']) && !empty($_GET['read']) ? strip_tags($_GET['read']) : null;

//$user = MySiteUser::getCurrentUser();
//$articles = $user->getArticles($page, $category, $read);


$html = new HTML();
$html->renderOut('site/html_header', array(
    'title' => '订阅文章 :: ' . $settings['sitename'],
    'body_class' => 'articles'
));
$html->renderOut('site/header/subscription', array(
    'read' => $read,
    'category' => $category
));
$html->renderOut('site/nav/main');
$html->renderOut('site/articles', array(
    'page' => $page ? $page : 1,
    'category' => $category,
    'read' => $read
));
$html->renderOut('site/footer');
$html->renderOut('site/html_footer');

