<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';


/**
 * DB fields
 * - id
 * - account_id
 * - category_id
 */
class UserWechatAccount extends DBObject {
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
    $this->table_name = "user_" . $user->getId() . "_account";
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
   public function setAccountId($var) {
     $this->setDbFieldAccount_id($var);
   }
   public function getAccountId() {
     return $this->getDbFieldAccount_id();
   }
   public function setCategoryId($var) {
     $this->setDbFieldCategory_id($var);
   }
   public function getCategoryId() {
     return $this->getDbFieldCategory_id();
   }

  
  
  /**
   * self functions
   */
  static function findById($id, $instance = 'UserWechatAccount') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = 'SELECT * FROM user_'.$user->getId().'_account WHERE id=' . $id;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findByWechatAccountId($wid) {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = 'SELECT * FROM user_'.$user->getId().'_account WHERE account_id=' . DBObject::prepare_val_for_sql($wid);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new UserWechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findAll() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_account";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new UserWechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_account LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new UserWechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT COUNT(*) as 'count' FROM user_".$user->getId()."_account";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "TRUNCATE TABLE user_".$user->getId()."_account";
    return $mysqli->query($query);
  }
  
  public function getWechatAccount() {
    return WechatAccount::findById($this->getAccountId());
  }
  
  public function getCategory() {
    return UserWechatCategory::findById($this->getCategoryId());
  }
  
  public function save() {
    $rtn = parent::save();
    
    // create extra relationship for wechat_account_user if not existed
    if ($rtn) {
      $user = MySiteUser::getCurrentUser();
      
      $wechat_account_user = WechatAccountUser::findbyCombo($this->getAccountId(), $user->getId());
      if (!$wechat_account_user) {
        $wechat_account_user = new WechatAccountUser();
        $wechat_account_user->setAccountId($this->getAccountId());
        $wechat_account_user->setUserId($user->getId());
        $wechat_account_user->save();
      }
    }
    
    return $rtn;
  }
  
  public function delete() {
    $rtn = parent::delete();
    
    // delete extra relationship for wechat_account_user if existed
    if ($rtn) {
      $user = MySiteUser::getCurrentUser();
      
      $wechat_account_user = WechatAccountUser::findbyCombo($this->getAccountId(), $user->getId());
      if ($wechat_account_user) {
        $wechat_account_user->delete();
      }
    }
    
    // check if there is any other users subscribing this wechat_account, if no, we delete it
    if ($rtn) {
      $wechat_account_users = WechatAccountUser::findByAccountId($this->getAccountId());
      if (sizeof($wechat_account_users) == 0) {
        $wechat_account = WechatAccount::findById($this->getAccountId());
        $wechat_account->delete();
      }
    }
    
    return $rtn;
  }
}