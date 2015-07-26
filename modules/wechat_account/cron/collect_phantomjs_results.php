<?php

/**
 * This script makes up shell scripts that calls phantomjs
 */
require __DIR__ . "/../../../bootstrap.php";

if (!is_cli()) {
  exit();
}

$how_many_processed_once = 15;


$result_dir = CACHE_DIR . DS . 'phantomjs_results';
if ($handle = opendir($result_dir)) {
  $i = 0;
  while (false !== ($entry = readdir($handle))) {
    if (preg_match('/^account_craw/', $entry) && $i < $how_many_processed_once) {
      $file = $result_dir . DS . $entry;
      
      // get created date from file name
      $matches = array();
      preg_match('/_((\d+)\-\d+\-\d+)\-/', $file, $matches);
      $date = $matches[1];
      $year = $matches[2];
      // get wechat_id from file name
      $matches = array();
      preg_match('/account_crawl_([^_]+)_/', $file, $matches);
      $wechat_id = $matches[1];
      $wechat_account = WechatAccount::findByWechatId($wechat_id);
      if ($wechat_account == null) {
        $log = new Log('wechat_account', Log::ERROR, 'Collect error: can not find wechat_account with wechat_id:' . $wechat_id);
        unlink($file);
        exit;
      }
      
      // parse html
      load_library_simple_html_dom();
      $html = file_get_contents($file);
      unlink($file);
      if (strpos($html, '[Error]') !== FALSE) {
        $log = new Log('wechat_account', Log::ERROR, $html);
        $log->save();
      } else {
        $dom = str_get_html($html);
        foreach ($dom->find('.wx-rb3') as $article) {
          
          /** extract vars from html **/
          $title = $article->find('.txt-box h4 a')[0]->innertext;
          $url = $article->find('.txt-box h4 a')[0]->href;
          
          $matches = array();
          preg_match('/_biz=([^&]+)/', $url, $matches);
          $bizid = $matches[1];
          
          $matches = array();
          preg_match('/mid=([^&]+)/', $url, $matches);
          $mid = $matches[1];
          
          $matches = array();
          preg_match('/idx=([^&]+)/', $url, $matches);
          $idx = $matches[1];
          
          $published_at = $article->find('.txt-box .s-p')[0]->innertext;
          $original_timezone = date_default_timezone_get();
          date_default_timezone_set('Asia/Shanghai');
          // when it is today
          if (preg_match('/\d+:\d+/', $published_at)) {
            $published_at = strtotime($date . ' ' . $published_at);
          // when it is not today
          } else if (preg_match('/月/', $published_at)) {
            $published_at = preg_replace('/(月|日)/', '-', $published_at);
            $published_at = trim($published_at, ' -');
            $published_at = $year . '-' . $published_at . ' 00:00:00';
            $published_at = strtotime($published_at);
          }
          date_default_timezone_set($original_timezone);
          
          $thumbnail = $article->find('.img_box2 img')[0]->src;
          
          /** check if we have this article crawled alread, if yes, skip **/
          $wechat_article = WechatArticle::findByCombo($bizid, $mid, $idx);
          if ($wechat_article) {
            continue;
          }
          
          $wechat_article = new WechatArticle();
          $wechat_article->setAccountId($wechat_account->getId());
          $wechat_article->setBizId($bizid);
          $wechat_article->setMid($mid);
          $wechat_article->setIdx($idx);
          $wechat_article->setTitle($title);
          $wechat_article->setPublishedAt($published_at);
          $wechat_article->setThumbnail($thumbnail);
          $wechat_article->setUrl($url);
          $wechat_article->save();
          
        }
        $dom->clear();
      }
      $i++;
    } else {
      continue;
    }
  }
  closedir($handle);
}
