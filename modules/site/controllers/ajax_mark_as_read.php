<?php

require_login();

$id = strip_tags($_POST['id']);
$read = strip_tags($_POST['read']) == '1' ? 1 : 0;
$user_wechat_account_id = strip_tags($_POST['user_wechat_account_id']);

$response = new stdClass();

if ($id) {
  // mark as read
  if ($read) {
    $user_read = new UserWechatRead();
    $user_read->setUserWechatAccountId($user_wechat_account_id);
    $user_read->setArticleId($id);
    if ($user_read->save()) {
      $response->status = 'success';
      $response->read = 1;
    } else {
      $response->status = 'error';
      $response->message = '标记为已读出错';
    }
  // mark as unread
  } else {
    $user_read = UserWechatRead::findByArticleId($id);
    if ($user_read && $user_read->delete()) {
      $response->status = 'success';
      $response->read = 0;
    } else {
      $response->status = 'error';
      $response->message = '标记为未读出错';
    }
  }
  

} else {
  $response->status = 'error';
  $response->message = '请提交标记文章的ID';
}

header('Content-Type: application/json');
echo json_encode($response);