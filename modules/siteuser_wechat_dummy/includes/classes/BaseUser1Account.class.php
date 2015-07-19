<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - account_id
 * - category_id
 */
class BaseUser1Account extends DBObject {
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
    $this->table_name = 'user_1_account';
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
  static function dropTable() {
    return parent::dropTableByName('user_1_account');
  }
  
  static function tableExist() {
    return parent::tableExistByName('user_1_account');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `user_1_account` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_id` INT ,
  `category_id` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-user_1_account-account_id-idx` (`account_id` ASC),
CONSTRAINT `fk-user_1_account-account_id`
  FOREIGN KEY (`account_id`)
  REFERENCES `wechat_account` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-user_1_account-category_id-idx` (`category_id` ASC),
CONSTRAINT `fk-user_1_account-category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `user_1_category` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'User1Account') {
    global $mysqli;
    $query = 'SELECT * FROM user_1_account WHERE id=' . $id;
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
    $query = "SELECT * FROM user_1_account";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new User1Account();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM user_1_account LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new User1Account();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM user_1_account";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE user_1_account";
    return $mysqli->query($query);
  }
}