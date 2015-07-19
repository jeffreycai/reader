<?php

$id = isset($vars[1]) ? $vars[1] : null;
$object = WechatAccount::findById($id);
if (is_null($object)) {
  HTML::forward('core/404');
}

// handle form submission
if (isset($_POST['submit'])) {
  $error_flag = false;

  /// validation
  
  // validation for $nickname
  $nickname = isset($_POST["nickname"]) ? strip_tags($_POST["nickname"]) : null;
  if (empty($nickname)) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "nickname is required.", "zh" => "请填写nickname"))));
    $error_flag = true;
  }
  
  // validation for $wechat_id
  $wechat_id = isset($_POST["wechat_id"]) ? strip_tags($_POST["wechat_id"]) : null;
  if (empty($wechat_id)) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "wechat_id is required.", "zh" => "请填写wechat_id"))));
    $error_flag = true;
  }
  
  // validation for $openid
  $openid = isset($_POST["openid"]) ? strip_tags($_POST["openid"]) : null;
  if (empty($openid)) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "openid is required.", "zh" => "请填写openid"))));
    $error_flag = true;
  }
  
  // validation for $introduction
  $introduction = isset($_POST["introduction"]) ? $_POST["introduction"] : null;  
  // validation for $cirtification
  $cirtification = isset($_POST["cirtification"]) ? $_POST["cirtification"] : null;  
  // validation for $qr_code
  $qr_code = isset($_POST["qr_code"]) ? strip_tags($_POST["qr_code"]) : null;
  if (empty($qr_code)) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "qr_code is required.", "zh" => "请填写qr_code"))));
    $error_flag = true;
  }
  
  // validation for $logo
  $logo = isset($_POST["logo"]) ? strip_tags($_POST["logo"]) : null;
  if (empty($logo)) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "logo is required.", "zh" => "请填写logo"))));
    $error_flag = true;
  }
  
  // validation for $active
  $active = isset($_POST["active"]) ? 1 : 0;  
  // validation for $last_updated
  $last_updated = isset($_POST["last_updated"]) ? strip_tags($_POST["last_updated"]) : null;  /// proceed submission
  
  // proceed for $nickname
  $object->setNickname($nickname);
  
  // proceed for $wechat_id
  $object->setWechatId($wechat_id);
  
  // proceed for $openid
  $object->setOpenid($openid);
  
  // proceed for $introduction
  $object->setIntroduction($introduction);
  
  // proceed for $cirtification
  $object->setCirtification($cirtification);
  
  // proceed for $qr_code
  $object->setQrCode($qr_code);
  
  // proceed for $logo
  $object->setLogo($logo);
  
  // proceed for $active
  $object->setActive($active);
  
  // proceed for $last_updated
  $object->setLastUpdated($last_updated);
  if ($error_flag == false) {
    if ($object->save()) {
      Message::register(new Message(Message::SUCCESS, i18n(array("en" => "Record saved", "zh" => "记录保存成功"))));
      HTML::forwardBackToReferer();
    } else {
      Message::register(new Message(Message::DANGER, i18n(array("en" => "Record failed to save", "zh" => "记录保存失败"))));
    }
  }
}



$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array(
  'en' => 'Edit Wechat Account',
  'zh' => 'Edit 微信公共账号',
  )),
));
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');


$html->renderOut('wechat_account/backend/wechat_account_edit', array(
  'object' => $object
));


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

