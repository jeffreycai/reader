<?php

$response = new stdClass();
if (!has_permission('manager feeds')) {
  $response->status = 'error';
  $response->message = '您没有权限进行此操作';
  echo json_encode($response);
  exit;
}

$openid = isset($_POST['openid']) ? strip_tags($_POST['openid']) : null;
$wechatid = isset($_POST['wechatid']) ? strip_tags($_POST['wechatid']) : null;
$description = isset($_POST['description']) ? strip_tags($_POST['description']) : null;
$certification = isset($_POST['certification']) ? strip_tags($_POST['certification']) : null;
$nickname = isset($_POST['nickname']) ? strip_tags($_POST['nickname']) : null;
$qrcode = isset($_POST['qrcode']) ? strip_tags($_POST['qrcode']) : null;
$logo = isset($_POST['logo']) ? strip_tags($_POST['logo']) : null;


// if wechat account hasn't been added globally, we add it
$wechat_account = WechatAccount::findByOpenid($openid);
if ($wechat_account == null) {
  $wechat_account = new WechatAccount();
  $wechat_account->setDescription($description);
  $wechat_account->setCertification($certification);
  $wechat_account->setOpenid($openid);
  $wechat_account->setWechatId($wechatid);
  $wechat_account->setLogo($logo);
  $wechat_account->setQrCode($qrcode);
  $wechat_account->setNickname($nickname);
  $wechat_account->save();
}

$user_wechat_account = UserWechatAccount::findByWechatAccountId($wechat_account->getId());
if ($user_wechat_account) {
  $response->status = 'error';
  $response->message = '此公众号已添加';
} else {
  $user_wechat_account = new UserWechatAccount();
  $user_wechat_account->setAccountId($wechat_account->getId());
  $user_wechat_account->setCategoryId(1);
  if ($user_wechat_account->save()) {
    $response->status = 'success';
  } else {
    $response->status = 'error';
    $response->message = '保存公众号出错';
  }
}

echo json_encode($response);
exit;



