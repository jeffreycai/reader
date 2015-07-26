<?php
require_once "BaseWechatArticle.class.php";

class WechatArticle extends BaseWechatArticle {
  static function findByCombo($bizid, $mid, $idx, $instance="WechatArticle") {
    global $mysqli;
    $query = "SELECT * FROM wechat_article WHERE biz_id=" . DBObject::prepare_val_for_sql($bizid) . " AND mid=" . DBObject::prepare_val_for_sql($mid) . " AND idx=" . $idx . " LIMIT 1";
    $result = $mysqli->query($query);
    
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  public function getWechatAccount() {
    return WechatAccount::findById($this->getAccountId());
  }
}
