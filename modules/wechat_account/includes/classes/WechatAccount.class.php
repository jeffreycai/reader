<?php
require_once "BaseWechatAccount.class.php";

class WechatAccount extends BaseWechatAccount {
  static function import($openid) {
    
  }
  
  static function findByOpenid($openid) {
    global $mysqli;
    $query = 'SELECT * FROM wechat_account WHERE openid=' . DBObject::prepare_val_for_sql($openid);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new WechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
}
