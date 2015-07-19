<?php



// override this call if "site" module has the override controller
$override_controller = MODULESROOT . '/site/controllers/confirm.php';
if (is_file($override_controller)) {
  require $override_controller;
  exit;
}





// default view
$html = new HTML();

$html->renderOut('core/backend/single_form_header', array(
    'title' => i18n(array(
        'en' => 'Hello',
        'zh' => '你好'
    ))
));
$messages = Message::renderMessages();
echo empty($messages) ? i18n(array(
    'en' => '<i>No messages</i>',
    'zh' => '<i>暂时没有信息</i>'
)) : $messages;
$html->renderOut('core/backend/single_form_footer', array(
    'extra' => '<div  style="text-align: center;"><small><a href="'.uri('').'">'.i18n(array('en' => 'go back to homepage', 'zh' => '返回首页')).'</a></small></div>'
));