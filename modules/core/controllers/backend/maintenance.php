<?php
$maintenance = Vars::findByName('maintenance');
$maintenance = $maintenance ? $maintenance->getValue() : 0;

// handle form submission
if (isset($_POST['submit'])) {
  $switch = isset($_POST['switch']) && $_POST['switch'] == 1 ? 1 : 0;
  
  $var = new Vars();
  $var->setName('maintenance');
  $var->setValue($switch);
  $var->save();
  
  Message::register(new Message(Message::SUCCESS, i18n(array(
      'en' => 'Maintenance mode updated',
      'zh' => '系统维护模式已更新'
  ))));
  
  HTML::forward('admin/maintenance');
  exit;
}

// presentation
$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array('en' => 'Maintenance mode', 'zh' => '系统维护设置')),
), true);
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');

$html->renderOut('core/backend/maintenance', array('maintenance' => $maintenance));


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;