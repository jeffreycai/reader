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
    if (UserWechatCategory::findById(1) == null) {
      $category = new UserWechatCategory();
      $category->setName('未分类');
      $category->save();
    }
  }
  
  public function delete() {
    $table_name_prefix = 'user_' . $this->getId();

    // delete all his wechat_account first, as the deletion can cast onto global accounts
    foreach (UserWechatAccount::findAll($this->getId()) as $account) {
      $account->delete();
    }
    
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
  `user_wechat_account_id` INT ,
  PRIMARY KEY (`id`)
 ,
INDEX `fk-".$table_name_prefix."_read-article_id-idx` (`article_id` ASC),
CONSTRAINT `fk-".$table_name_prefix."_read-article_id`
  FOREIGN KEY (`article_id`)
  REFERENCES `wechat_article` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ,
INDEX `fk-".$table_name_prefix."_read-user_wechat_account_id-idx` (`user_wechat_account_id` ASC),
CONSTRAINT `fk-".$table_name_prefix."_read-user_wechat_account_id`
  FOREIGN KEY (`user_wechat_account_id`)
  REFERENCES `".$table_name_prefix."_account` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
");
    }
  }
  
  public function getArticles($page = 1, $category = null, $unread = null) {
    global $mysqli;
    
    $user_account_table = 'user_' . $this->getId() . '_account';
    $user_category_table = 'user_' . $this->getId() . '_category';
    $user_read_table = 'user_' . $this->getId() . '_read';
    
    // where
    $where = array();
    if ($category) {
      $where[] = " ua.category_id=$category ";
    }
    if (sizeof($where)) {
      $where = " WHERE " . implode(' AND ', $where) . " ";
      if ($unread == 1) {
        $where .= " AND ur.article_id IS NULL ";
      }
    } else {
      $where = '';
      if ($unread == 1) {
        $where = " WHERE ur.article_id IS NULL";
      }
    }
    
    
    // join
    $join = "";
    if ($unread == 1) {
      $join = " LEFT JOIN $user_read_table as ur ON ur.article_id=wa.id ";
    }
    
    // limit
    $limit = "";
    if ($page) {
      $settings = Vars::getSettings();
      $limit = " LIMIT " . ($page - 1) * $settings['articles_per_page'] . ", " . $settings['articles_per_page'];
    }
    
    // order by
    $order_by = ' ORDER BY wa.published_at DESC ';
    
    ///// final query
    $query = "SELECT wa.*, ua.id AS user_wechat_account_id FROM wechat_article as wa JOIN $user_account_table as ua ON ua.account_id=wa.account_id $join $where $order_by $limit";
//_debug($query);
    $result = $mysqli->query($query);
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new WechatArticle();
      DBObject::importQueryResultToDbObject($b, $obj);
      $obj->user_wechat_account_id = $b->user_wechat_account_id;
      $rtn[] = $obj;
    }
    
    return $rtn;
  }

}