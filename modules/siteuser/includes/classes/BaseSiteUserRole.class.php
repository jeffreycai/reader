<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - user_id
 * - role_id
 */
class BaseSiteUserRole extends DBObject {
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
    $this->table_name = 'site_user_role';
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
   public function setUserId($var) {
     $this->setDbFieldUser_id($var);
   }
   public function getUserId() {
     return $this->getDbFieldUser_id();
   }
   public function setRoleId($var) {
     $this->setDbFieldRole_id($var);
   }
   public function getRoleId() {
     return $this->getDbFieldRole_id();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('site_user_role');
  }
  
  static function tableExist() {
    return parent::tableExistByName('site_user_role');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `site_user_role` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `role_id` INT NOT NULL ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-site_user_role-user_id-idx` (`user_id` ASC),
CONSTRAINT `fk-site_user_role-user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `site_user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-site_user_role-role_id-idx` (`role_id` ASC),
CONSTRAINT `fk-site_user_role-role_id`
  FOREIGN KEY (`role_id`)
  REFERENCES `site_role` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'SiteUserRole') {
    global $mysqli;
    $query = 'SELECT * FROM site_user_role WHERE id=' . $id;
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
    $query = "SELECT * FROM site_user_role";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SiteUserRole();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM site_user_role LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SiteUserRole();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM site_user_role";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE site_user_role";
    return $mysqli->query($query);
  }
}