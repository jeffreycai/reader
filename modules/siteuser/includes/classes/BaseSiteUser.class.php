<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - username
 * - email
 * - salt
 * - password
 * - active
 * - email_activated
 * - created_at
 * - last_login
 */
class BaseSiteUser extends DBObject {
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
    $this->table_name = 'site_user';
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
   public function setUsername($var) {
     $this->setDbFieldUsername($var);
   }
   public function getUsername() {
     return $this->getDbFieldUsername();
   }
   public function setEmail($var) {
     $this->setDbFieldEmail($var);
   }
   public function getEmail() {
     return $this->getDbFieldEmail();
   }
   public function setSalt($var) {
     $this->setDbFieldSalt($var);
   }
   public function getSalt() {
     return $this->getDbFieldSalt();
   }
   public function setPassword($var) {
     $this->setDbFieldPassword($var);
   }
   public function getPassword() {
     return $this->getDbFieldPassword();
   }
   public function setActive($var) {
     $this->setDbFieldActive($var);
   }
   public function getActive() {
     return $this->getDbFieldActive();
   }
   public function setEmailActivated($var) {
     $this->setDbFieldEmail_activated($var);
   }
   public function getEmailActivated() {
     return $this->getDbFieldEmail_activated();
   }
   public function setCreatedAt($var) {
     $this->setDbFieldCreated_at($var);
   }
   public function getCreatedAt() {
     return $this->getDbFieldCreated_at();
   }
   public function setLastLogin($var) {
     $this->setDbFieldLast_login($var);
   }
   public function getLastLogin() {
     return $this->getDbFieldLast_login();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('site_user');
  }
  
  static function tableExist() {
    return parent::tableExistByName('site_user');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `site_user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(24) NOT NULL UNIQUE ,
  `email` VARCHAR(128) NOT NULL UNIQUE ,
  `salt` VARCHAR(16) NOT NULL ,
  `password` VARCHAR(32) ,
  `active` TINYINT(1) DEFAULT 1 ,
  `email_activated` TINYINT(1) DEFAULT 1 ,
  `created_at` INT NOT NULL ,
  `last_login` INT ,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'SiteUser') {
    global $mysqli;
    $query = 'SELECT * FROM site_user WHERE id=' . $id;
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
    $query = "SELECT * FROM site_user";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SiteUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM site_user LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SiteUser();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM site_user";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE site_user";
    return $mysqli->query($query);
  }
}