<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - permission_id
 * - role_id
 */
class BaseSitePermissionRole extends DBObject {
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
    $this->table_name = 'site_permission_role';
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
   public function setPermissionId($var) {
     $this->setDbFieldPermission_id($var);
   }
   public function getPermissionId() {
     return $this->getDbFieldPermission_id();
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
    return parent::dropTableByName('site_permission_role');
  }
  
  static function tableExist() {
    return parent::tableExistByName('site_permission_role');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `site_permission_role` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `permission_id` INT NOT NULL ,
  `role_id` INT NOT NULL ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-site_permission_role-permission_id-idx` (`permission_id` ASC),
CONSTRAINT `fk-site_permission_role-permission_id`
  FOREIGN KEY (`permission_id`)
  REFERENCES `site_permission` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-site_permission_role-role_id-idx` (`role_id` ASC),
CONSTRAINT `fk-site_permission_role-role_id`
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
  
  static function findById($id, $instance = 'SitePermissionRole') {
    global $mysqli;
    $query = 'SELECT * FROM site_permission_role WHERE id=' . $id;
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
    $query = "SELECT * FROM site_permission_role";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SitePermissionRole();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM site_permission_role LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SitePermissionRole();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM site_permission_role";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE site_permission_role";
    return $mysqli->query($query);
  }
}