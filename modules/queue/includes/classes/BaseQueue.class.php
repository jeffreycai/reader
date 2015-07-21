<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - created_at
 * - started_at
 * - finished_at
 * - status
 * - status_info
 * - arguments
 * - function
 * - type
 * - priority
 * - description
 */
class BaseQueue extends DBObject {
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
    $this->table_name = 'queue';
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
   public function setCreatedAt($var) {
     $this->setDbFieldCreated_at($var);
   }
   public function getCreatedAt() {
     return $this->getDbFieldCreated_at();
   }
   public function setStartedAt($var) {
     $this->setDbFieldStarted_at($var);
   }
   public function getStartedAt() {
     return $this->getDbFieldStarted_at();
   }
   public function setFinishedAt($var) {
     $this->setDbFieldFinished_at($var);
   }
   public function getFinishedAt() {
     return $this->getDbFieldFinished_at();
   }
   public function setStatus($var) {
     $this->setDbFieldStatus($var);
   }
   public function getStatus() {
     return $this->getDbFieldStatus();
   }
   public function setStatusInfo($var) {
     $this->setDbFieldStatus_info($var);
   }
   public function getStatusInfo() {
     return $this->getDbFieldStatus_info();
   }
   public function setArguments($var) {
     $this->setDbFieldArguments($var);
   }
   public function getArguments() {
     return $this->getDbFieldArguments();
   }
   public function setFunction($var) {
     $this->setDbFieldFunction($var);
   }
   public function getFunction() {
     return $this->getDbFieldFunction();
   }
   public function setType($var) {
     $this->setDbFieldType($var);
   }
   public function getType() {
     return $this->getDbFieldType();
   }
   public function setPriority($var) {
     $this->setDbFieldPriority($var);
   }
   public function getPriority() {
     return $this->getDbFieldPriority();
   }
   public function setDescription($var) {
     $this->setDbFieldDescription($var);
   }
   public function getDescription() {
     return $this->getDbFieldDescription();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('queue');
  }
  
  static function tableExist() {
    return parent::tableExistByName('queue');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `queue` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created_at` INT NOT NULL ,
  `started_at` INT DEFAULT NULL ,
  `finished_at` INT DEFAULT NULL ,
  `status` TINYINT(1) ,
  `status_info` TEXT ,
  `arguments` TEXT ,
  `function` VARCHAR(20) ,
  `type` VARCHAR(15) NOT NULL ,
  `priority` TINYINT DEFAULT 0 ,
  `description` TEXT ,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'Queue') {
    global $mysqli;
    $query = 'SELECT * FROM queue WHERE id=' . $id;
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
    $query = "SELECT * FROM queue";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new Queue();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM queue LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new Queue();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM queue";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE queue";
    return $mysqli->query($query);
  }
}