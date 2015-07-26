<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - article_id
 * - user_wechat_account_id
 */
class UserWechatRead extends DBObject {
  /**
   * Implement parent abstract functions
   */
  protected function setPrimaryKeyName() {
    $this->primary_key = array(
      'id'
    );
  }
  protected function setPrimaryKeyAutoIncreased() {
    $this->pk_auto_increased = TRUE;
  }
  protected function setTableName() {
    $user = MySiteUser::getCurrentUser();
    $this->table_name = 'user_'.$user->getId().'_read';
  }
  
  /**
   * Setters and getters
   */
   public function setId($var) {
     $this->setDbFieldId($var);
   }
   public function getId() {
     return $this->getDbFieldId();
   }
   public function setArticleId($var) {
     $this->setDbFieldArticle_id($var);
   }
   public function getArticleId() {
     return $this->getDbFieldArticle_id();
   }
   public function setUserWechatAccountId($var) {
     $this->setDbFieldUser_wechat_account_id($var);
   }
   public function getUserWechatAccountId() {
     return $this->getDbFieldUser_wechat_account_id();
   }

  
  
  /**
   * self functions
   */
  static function findById($id, $instance = 'UserWechatRead') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = 'SELECT * FROM user_'.$user->getId().'_read WHERE id=' . $id;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findByArticleId($aid, $instance = 'UserWechatRead') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = 'SELECT * FROM user_'.$user->getId().'_read WHERE article_id=' . $aid;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findAll($instance = 'UserWechatRead') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_read";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page, $instance = 'UserWechatRead') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_read LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT COUNT(*) as 'count' FROM user_".$user->getId()."_read";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "TRUNCATE TABLE user_".$user->getId()."_read";
    return $mysqli->query($query);
  }
}