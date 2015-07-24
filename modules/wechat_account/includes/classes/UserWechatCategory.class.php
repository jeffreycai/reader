<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - name
 */
class UserWechatCategory extends DBObject {
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
    $this->table_name = 'user_'.$user->getId().'_category';
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
   public function setName($var) {
     $this->setDbFieldName($var);
   }
   public function getName() {
     return $this->getDbFieldName();
   }

  
  
  /**
   * self functions
   */
  static function findById($id, $instance = 'UserWechatCategory') {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = 'SELECT * FROM user_'.$user->getId().'_category WHERE id=' . $id;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findAll() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_category";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new UserWechatCategory();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT * FROM user_".$user->getId()."_category LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new UserWechatCategory();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "SELECT COUNT(*) as 'count' FROM user_".$user->getId()."_category";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $user = MySiteUser::getCurrentUser();
    
    $query = "TRUNCATE TABLE user_".$user->getId()."_category";
    return $mysqli->query($query);
  }
}