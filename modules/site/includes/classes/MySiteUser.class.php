<?php
require_once MODULESROOT . DS . 'siteuser' . DS . 'includes' . DS . 'classes' . DS . 'SiteUser.class.php';

class MySiteUser extends SiteUser {
  
  public function login($remember) {
    parent::login($remember);
    
    /** check the user tables exists, if not, create **/
    global $mysqli;
    $table_name_prefix = 'user_' . $this->getId();
    self::createCategoryTableIfNotExist($table_name_prefix);
    self::createAccountTableIfNotExist($table_name_prefix);
    self::createReadTableIfNotExist($table_name_prefix);
    
    // add default category none
    $category = new UserWechatCategory();
    $category->setName('未分类');
    $category->save();
  }
  
  public function delete() {
    $table_name_prefix = 'user_' . $this->getId();

    // delete user tables
    parent::dropTableByName($table_name_prefix . '_read');
    parent::dropTableByName($table_name_prefix . '_account');
    parent::dropTableByName($table_name_prefix . '_category');
    
    return parent::delete();
  }
  
  static function createCategoryTableIfNotExist($table_name_prefix) {
    global $mysqli;
    
    if (!parent::tableExistByName($table_name_prefix . '_category')) {
      return $mysqli->query("
CREATE TABLE IF NOT EXISTS `".$table_name_prefix."_category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) ,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
");
    }
  }
  
  static function createAccountTableIfNotExist($table_name_prefix) {
    global $mysqli;
    
    if (!parent::tableExistByName($table_name_prefix . '_account')) {
      return $mysqli->query("
CREATE TABLE IF NOT EXISTS `".$table_name_prefix."_account` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_id` INT ,
  `category_id` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-".$table_name_prefix."_account-account_id-idx` (`account_id` ASC),
CONSTRAINT `fk-".$table_name_prefix."_account-account_id`
  FOREIGN KEY (`account_id`)
  REFERENCES `wechat_account` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-".$table_name_prefix."_account-category_id-idx` (`category_id` ASC),
CONSTRAINT `fk-".$table_name_prefix."_account-category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `".$table_name_prefix."_category` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
");
    }
  }
  
  static function createReadTableIfNotExist($table_name_prefix) {
    global $mysqli;
    
    if (!parent::tableExistByName($table_name_prefix . '_read')) {
      return $mysqli->query("
CREATE TABLE IF NOT EXISTS `".$table_name_prefix."_read` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `article_id` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-".$table_name_prefix."_read-article_id-idx` (`article_id` ASC),
CONSTRAINT `fk-".$table_name_prefix."_read-article_id`
  FOREIGN KEY (`article_id`)
  REFERENCES `wechat_article` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
");
    }
  }
  
  

}