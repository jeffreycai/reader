<?php

require_permission('manager feeds');

// handle submission
$pager = null;
$articles = null;
if (isset($_GET['keyword'])) {
  $crawler = new Crawler();
  $result = $crawler->read("http://weixin.sogou.com/weixin?query=".urlencode($_GET['keyword'])."&fr=sgsearch&type=1" . (isset($_GET['page']) ? "&page=" . strip_tags($_GET['page']) : ''));
  // clean up first
  $result = preg_replace('/<\/?em>/', '', $result);
  $result = preg_replace('/<!--[^-]+-->/', '', $result);
  $result = preg_replace('/<script[^<]+<\/script>/', '', $result);
  $articles = array();
  // get titles
  $matches = array();
  preg_match_all('/<h3>(.+)<\/h3>/', $result, $matches);
  if (isset($matches[1])) {
    for ($i = 0; $i < sizeof($matches[1]); $i++) {
      $articles[$i]['title'] = $matches[1][$i];
    }
  }
  // get wechat_account
  $matches = array();
  preg_match_all('/<span>微信号：(.+)?<\/span>/', $result, $matches);
  if (isset($matches[1])) {
    for ($i = 0; $i < sizeof($matches[1]); $i++) {
      $articles[$i]['account'] = $matches[1][$i];
    }
  }
  // get openid
  $matches = array();
  preg_match_all('/\/gzh\?openid=([^\']+)\',event/', $result, $matches);
  if (isset($matches[1])) {
    for ($i = 0; $i < sizeof($matches[1]); $i++) {
      $articles[$i]['openid'] = $matches[1][$i];
    }
  }
  // get thumb
  $matches = array();
  preg_match_all('/<img style="visibility:hidden" src="([^"]+)"/', $result, $matches);
  if (isset($matches[1])) {
    for ($i = 0; $i < sizeof($matches[1]); $i++) {
      $articles[$i]['thumb'] = $matches[1][$i];
    }
  }
  // get qrcode
  $matches = array();
  preg_match_all('/<img width="140".+src="([^"]+)"/', $result, $matches);
  if (isset($matches[1])) {
    for ($i = 0; $i < sizeof($matches[1]); $i++) {
      $articles[$i]['qrcode'] = $matches[1][$i];
    }
  }
  // get intro
  $matches = array();
  preg_match_all('/<span class="sp-t.t">.+<\/span>/', $result, $matches);
  if (isset($matches[0])) {
    $idx = -1;
    for ($i = 0; $i < sizeof($matches[0]); $i++) {
      if (strpos($matches[0][$i], '功能介绍') !== false) {
        $idx++;
        $articles[$idx]['description'] = $matches[0][$i];
      }
      if (strpos($matches[0][$i], '认证') !== false) {
        $articles[$idx]['certification'] = $matches[0][$i];
      }
      if (strpos($matches[0][$i], '最近文章') !== false) {
        $articles[$idx]['latest'] = $matches[0][$i];
      }
    }
  }

  // get pager
  preg_match_all('/<div class="p" id="pagebar_container">.+<\/div>/', $result, $matches);
  if (isset($matches[0]) && isset($matches[0][0])) {
    $pager = $matches[0][0];
    $pager = preg_replace('/href="[^"]+page=(\d+)[^"]+"/', 'href="'.uri('accounts/search').'?keyword='.urlencode($_GET['keyword']).'&page=$1"', $pager);
  }
//  echo "<meta charset='utf-8'>";_debug($articles);
}





$html = new HTML();
$html->renderOut('site/html_header', array(
    'title' => '搜索公众号 :: ' . $settings['sitename'],
    'body_class' => 'accounts_search'
));
$html->renderOut('site/header/accounts_search');
$html->renderOut('site/accounts_search', array(
    'articles' => $articles,
    'pager' => $pager
));
$html->renderOut('site/footer');
$html->renderOut('site/html_footer');

