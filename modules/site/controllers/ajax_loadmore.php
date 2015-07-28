<?php

require_login();

$page = strip_tags($_POST['page']);
$category = strip_tags($_POST['category']);
$unread = strip_tags($_POST['unread']);

$user = MySiteUser::getCurrentUser();
$articles = $user->getArticles($page, $category, $unread);

$rtn = new stdClass();
$rtn->articles = array();
$rtn->page = $page + 1;
foreach ($articles as $article) {
  $a = new stdClass();
  $a->title = $article->getTitle();
  $a->thumbnail = $article->getThumbnail();
  $a->url = $article->getUrl();
  $a->wechat_id = $article->getWechatId();
  $a->id = $article->getId();
  $a->user_wechat_account_id = $article->user_wechat_account_id;
  
  $original_timezone = date_default_timezone_get();
  date_default_timezone_set('Asia/Shanghai');
  if ($article->getPublishedAt() > strtotime(date('Y-m-d'))) {
    $a->published_at = date('H:i', $article->getPublishedAt());
  } else  {
    $a->published_at = date('Y-m-d', $article->getPublishedAt());
  }
  
  $a->wechat_account = $article->getWechatAccount()->getNickname();
  
  if (UserWechatRead::findByArticleId($article->getId())) {
    $a->read = 1;
  } else {
    $a->read = 0;
  }
  
  $rtn->articles[] = $a;
}


header('Content-Type: application/json');
echo json_encode($rtn);