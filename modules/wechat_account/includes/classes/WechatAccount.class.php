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
  
  static function findByWechatId($wechat_id) {
    global $mysqli;
    $query = 'SELECT * FROM wechat_account WHERE wechat_id=' . DBObject::prepare_val_for_sql($wechat_id);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new WechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findAllToCrawl($span = 3600) {
    global $mysqli;
    $query = "SELECT * FROM wechat_account WHERE active=1 AND (last_scheduled IS NULL OR last_scheduled < ".(time()-$span).")";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
}
