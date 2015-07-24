<?php
include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - nickname
 * - wechat_id
 * - openid
 * - description
 * - certification
 * - qr_code
 * - logo
 * - active
 * - last_updated
 */
class BaseWechatAccount extends DBObject {
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
    $this->table_name = 'wechat_account';
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
   public function setNickname($var) {
     $this->setDbFieldNickname($var);
   }
   public function getNickname() {
     return $this->getDbFieldNickname();
   }
   public function setWechatId($var) {
     $this->setDbFieldWechat_id($var);
   }
   public function getWechatId() {
     return $this->getDbFieldWechat_id();
   }
   public function setOpenid($var) {
     $this->setDbFieldOpenid($var);
   }
   public function getOpenid() {
     return $this->getDbFieldOpenid();
   }
   public function setDescription($var) {
     $this->setDbFieldDescription($var);
   }
   public function getDescription() {
     return $this->getDbFieldDescription();
   }
   public function setCertification($var) {
     $this->setDbFieldCertification($var);
   }
   public function getCertification() {
     return $this->getDbFieldCertification();
   }
   public function setQrCode($var) {
     $this->setDbFieldQr_code($var);
   }
   public function getQrCode() {
     return $this->getDbFieldQr_code();
   }
   public function setLogo($var) {
     $this->setDbFieldLogo($var);
   }
   public function getLogo() {
     return $this->getDbFieldLogo();
   }
   public function setActive($var) {
     $this->setDbFieldActive($var);
   }
   public function getActive() {
     return $this->getDbFieldActive();
   }
   public function setLastUpdated($var) {
     $this->setDbFieldLast_updated($var);
   }
   public function getLastUpdated() {
     return $this->getDbFieldLast_updated();
   }

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('wechat_account');
  }
  
  static function tableExist() {
    return parent::tableExistByName('wechat_account');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `wechat_account` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nickname` VARCHAR(31) NOT NULL ,
  `wechat_id` VARCHAR(31) NOT NULL UNIQUE ,
  `openid` VARCHAR(31) NOT NULL UNIQUE ,
  `description` VARCHAR(1023) ,
  `certification` VARCHAR(1023) ,
  `qr_code` VARCHAR(127) NOT NULL ,
  `logo` VARCHAR(127) NOT NULL ,
  `active` TINYINT(1) DEFAULT 1 ,
  `last_updated` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `wechat_account_openid` (`openid` ASC) ,
INDEX `wechat_account_wechat_id` (`wechat_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = 'WechatAccount') {
    global $mysqli;
    $query = 'SELECT * FROM wechat_account WHERE id=' . $id;
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
    $query = "SELECT * FROM wechat_account";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM wechat_account LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatAccount();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM wechat_account";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE wechat_account";
    return $mysqli->query($query);
  }
}