<?php
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if (!preg_match('/^\d+$/', $page)) {
  dispatch('core/backend/404');
  exit;
}
$settings = Vars::getSettings();
$backend_perpage = 100;

$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Task list', 'zh' => '任务列表')),
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');

$total = Queue::countAll();
$total_page = ceil($total / $backend_perpage);
$html->renderOut('queue/backend/list', array(
    'queues' => Queue::findAllWithPage($page, $backend_perpage),
    'current_page' => $page,
    'total_page' => $total_page,
    'total' => $total,
    'pager' => $html->render('core/components/pagination', array('total' => $total_page, 'page' => $page)),
    'backend_perpage' => $backend_perpage
), true);


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;
