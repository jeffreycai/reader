<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - title
 * - published_at
 * - thumbnail
 * - url
 */
class BaseWechatArticle extends DBObject {
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
    $this->table_name = 'wechat_article';
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
   public function setTitle($var) {
     $this->setDbFieldTitle($var);
   }
   public function getTitle() {
     return $this->getDbFieldTitle();
   }
   public function setPublishedAt($var) {
     $this->setDbFieldPublished_at($var);
   }
   public function getPublishedAt() {
     return $this->getDbFieldPublished_at();
   }
   public function setThumbnail($var) {
     $this->setDbFieldThumbnail($var);
   }
   public function getThumbnail() {
     return $this->getDbFieldThumbnail();
   }
   public function setUrl($var) {
     $this->setDbFieldUrl($var);
   }
   public function getUrl() {
     return $this->getDbFieldUrl();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('wechat_article');
  }
  
  static function tableExist() {
    return parent::tableExistByName('wechat_article');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `wechat_article` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(1023) NOT NULL ,
  `published_at` INT NOT NULL ,
  `thumbnail` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'WechatArticle') {
    global $mysqli;
    $query = 'SELECT * FROM wechat_article WHERE id=' . $id;
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
    $query = "SELECT * FROM wechat_article";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatArticle();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM wechat_article LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatArticle();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM wechat_article";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE wechat_article";
    return $mysqli->query($query);
  }
}