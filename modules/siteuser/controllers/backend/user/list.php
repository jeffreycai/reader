<?php
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if (!preg_match('/^\d+$/', $page)) {
  dispatch('core/backend/404');
  exit;
}

$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Manage user', 'zh' => '管理用户')),
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');

$total = SiteUser::countAll();
$per_page = 50;
$total_page = ceil($total / $per_page);
$html->renderOut('siteuser/backend/user/list', array(
    'users' => SiteUser::findAllWithPage($page, $per_page),
    'current_page' => $page,
    'total_page' => $total_page,
    'total' => $total,
    'pager' => $html->render('core/components/pagination', array('total' => $total_page, 'page' => $page)),
    'per_page' => $per_page
), true);


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

