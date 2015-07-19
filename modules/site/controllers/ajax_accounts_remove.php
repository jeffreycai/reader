<?php

$openid = isset($_POST['openid']) ? strip_tags($_POST['openid']) : null;

$response = new stdClass();

// if wechat account hasn't been added globally, we add it
$wechat_account = WechatAccount::findByOpenid($openid);
if ($wechat_account == null) {
  $response->status = 'error';
  $response->message = '您未订阅此公众号，不可退订';
} else {
  $user_wechat_account = UserWechatAccount::findByWechatAccountId($wechat_account->getId());
  if ($user_wechat_account->delete()) {
    $response->status = 'success';
  } else {
    $response->status = 'error';
    $response->message = '删除订阅失败';
  }
}

echo json_encode($response);
exit;



