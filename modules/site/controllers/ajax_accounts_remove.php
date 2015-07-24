<?php

$response = new stdClass();
if (!has_permission('manager feeds')) {
  $response->status = 'error';
  $response->message = '您没有权限进行此操作';
  echo json_encode($response);
  exit;
}

$openid = isset($_POST['openid']) ? strip_tags($_POST['openid']) : null;
$wechat_account = WechatAccount::findByOpenid($openid);

// check if the account exists
if ($wechat_account == null) {
  $response->status = 'error';
  $response->message = '此公众号不存在，无法退订';
  echo json_encode($response);
  exit();
}

// check if the user has subscribed the account
$user_wechat_account = UserWechatAccount::findByWechatAccountId($wechat_account->getId());
if (!$user_wechat_account) {
  $response->status = 'error';
  $response->message = '您并没有订阅此公众号，无法退订';
  echo json_encode($response);
  exit();
}

// unsubscribe
if ($user_wechat_account->delete()) {
  $response->status = 'success';
} else {
  $response->status = 'error';
  $response->message = '删除订阅失败';
}

echo json_encode($response);
exit;



