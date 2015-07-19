<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - time
 * - module
 * - category
 * - ip
 * - content
 */
class Log extends DBObject {
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
    $this->table_name = 'log';
  }
  
  /**
   * Setters and getters
   */
  public function setId($id) {
    $this->setDbFieldId($id);
  }
  public function getId() {
    return $this->getDbFieldId();
  }
  public function setTime($time) {
    $this->setDbFieldTime($time);
  }
  public function getTime() {
    return $this->getDbFieldTime();
  }
  public function setModule($m) {
    $this->setDbFieldModule($m);
  }
  public function getModule() {
    return $this->getDbFieldModule();
  }
  public function setCategory($c) {
    $this->setDbFieldCategory($c);
  }
  public function getCategory() {
    return $this->getDbFieldCategory();
  }
  public function setIp($ip) {
    $this->setDbFieldIp($ip);
  }
  public function getIp() {
    return $this->getDbFieldIp();
  }
  public function setContent($c) {
    $this->setDbFieldContent($c);
  }
  public function getContent() {
    return $this->getDbFieldContent();
  }
  

  /**
   * self functions
   */
  const SUCCESS = 'SUCCESS';
  const WARNING = 'WARNING';
  const ERROR = 'ERROR';
  const NOTICE = 'NOTICE';
  
  
  static function dropTable() {
    return parent::dropTableByName('log');
  }
  
  static function tableExist() {
    return parent::tableExistByName('log');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `log` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `time` INT NOT NULL ,
  `module` VARCHAR(20) ,
  `category` VARCHAR(10) ,
  `ip` VARCHAR(15) ,
  `content` LONGTEXT ,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'Log') {
    global $mysqli;
    $query = 'SELECT * FROM log WHERE id=' . $id;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $log = new $instance();
      DBObject::importQueryResultToDbObject($b, $log);
      return $log;
    }
    return null;
  }
  
  static function findAll() {
    global $mysqli;
    $query = "SELECT * FROM log";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $log = new Log();
      DBObject::importQueryResultToDbObject($b, $log);
      $rtn[] = $log;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page, $order_by = 'time', $order = 'DESC') {
    global $mysqli;
    $query = "SELECT * FROM log ORDER BY $order_by $order LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $log = new Log();
      DBObject::importQueryResultToDbObject($b, $log);
      $rtn[] = $log;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM log";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  function __construct($module = null, $category = null, $content = null, $ip = 'localhost', $time = null) {
    parent::__construct();
    
    $this->setModule($module);
    $this->setCategory($category);
    $this->setContent($content);
    $this->setIp($ip);
    $this->setTime($time == null ? time() : $time);
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE log";
    return $mysqli->query($query);
  }
}