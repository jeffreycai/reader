<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - account_id
 * - user_id
 */
class BaseWechatAccountUser extends DBObject {
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
    $this->table_name = 'wechat_account_user';
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
   public function setUserId($var) {
     $this->setDbFieldUser_id($var);
   }
   public function getUserId() {
     return $this->getDbFieldUser_id();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('wechat_account_user');
  }
  
  static function tableExist() {
    return parent::tableExistByName('wechat_account_user');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `wechat_account_user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_id` INT ,
  `user_id` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `wechat_account_user_combo` (`account_id`,`user_id`) ,
INDEX `fk-wechat_account_user-account_id-idx` (`account_id` ASC),
CONSTRAINT `fk-wechat_account_user-account_id`
  FOREIGN KEY (`account_id`)
  REFERENCES `wechat_account` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-wechat_account_user-user_id-idx` (`user_id` ASC),
CONSTRAINT `fk-wechat_account_user-user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `site_user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'WechatAccountUser') {
    global $mysqli;
    $query = 'SELECT * FROM wechat_account_user WHERE id=' . $id;
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
    $query = "SELECT * FROM wechat_account_user";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccountUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM wechat_account_user LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccountUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM wechat_account_user";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE wechat_account_user";
    return $mysqli->query($query);
  }
}