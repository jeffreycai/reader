<?php
require_once "BaseWechatAccountUser.class.php";

class WechatAccountUser extends BaseWechatAccountUser {
  static function findbyCombo($account_id, $user_id) {
    global $mysqli;
    $query = 'SELECT * FROM wechat_account_user WHERE account_id=' . $account_id . ' AND user_id=' . $user_id . ' LIMIT 1';
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new WechatAccountUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findByAccountId($account_id) {
    global $mysqli;
    $query = "SELECT * FROM wechat_account_user WHERE account_id=" . $account_id;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccountUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
}
