<?php
include_once __DIR__ . DS . 'DBObject.class.php';

/**
 * DB fields
 * - id
 * - email
 * - name
 * - password
 * - salt
 */
class User extends DBObject {
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
    $this->table_name = 'user';
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
  public function setName($k) {
    $this->setDBFieldName($k);
  }
  public function getName() {
    return $this->getDbFieldName();
  }
  public function setPassword($p) {
    $this->setDBFieldPassword($this->hashPassword($p));
  }
  public function getPassword() {
    return $this->getDbFieldPassword();
  }
  public function setEmail($e) {
    $this->setDBFieldEmail($e);
  }
  public function getEmail() {
    return $this->getDbFieldEmail();
  }
  public function setSalt($salt) {
    $this->setDBFieldSalt($salt ? $salt : md5(time()));
  }
  public function getSalt() {
    return $this->getDbFieldSalt();
  }
  public function setRole($r) {
    $this->setDBFieldRole($r);
  }
  public function getRole() {
    return $this->getDbFieldRole();
  }
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('user');
  }
  
  static function tableExist() {
    return parent::tableExistByName('user');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;
    if (!self::tableExist()) {
      return $mysqli->query('
        CREATE  TABLE `user` (
          `id` INT NOT NULL AUTO_INCREMENT ,
          `email` VARCHAR(255) NOT NULL ,
          `name` VARCHAR(45) NOT NULL ,
          `password` VARCHAR(32) NOT NULL ,
          `salt` VARCHAR(32) NOT NULL ,
          `role` VARCHAR(32) NOT NULL ,
          PRIMARY KEY (`id`) ,
          UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function importFixture() {
    $settings = Vars::getSettings();
    // import user fixture from "role.yml"
    if (User::tableExist() && isset($settings['users'])) {
      foreach ($settings['users'] as $user) {
        $u = new User();
        $u->setEmail($user['email']);
        $u->setName($user['name']);
        $u->setPassword($user['password']);
        $u->setSalt($user['salt']);
        $roles = array();
        foreach ($user['role'] as $role) {
          $roles[] = $role;
        }
        $u->setRole(implode(',', $roles));
        $u->save();
      }
    }
  }
  
  static function findByEmail($email) {
    global $mysqli;
    $query = 'SELECT * FROM user WHERE email=' . DBObject::prepare_val_for_sql($email);
    $result = $mysqli->query($query);
    if ($result && $u = $result->fetch_object()) {
      $user = new User();
      DBObject::importQueryResultToDbObject($u, $user);
      return $user;
    }
    return null;
  }
  
  static function findById($id) {
    global $mysqli;
    $query = 'SELECT * FROM user WHERE id=' . $id;
    $result = $mysqli->query($query);
    if ($result && $u = $result->fetch_object()) {
      $user = new User();
      DBObject::importQueryResultToDbObject($u, $user);
      return $user;
    }
    return null;
  }

  /**
   * Get the current user instance
   */
  static function getInstance() {
    global $user;
    global $enabled_modules;
    if (isset($user)) {
      return $user;
    }
    
    if (isset($_SESSION['user_email'])) {
      $user = self::findByEmail($_SESSION['user_email']);
    } else {
      if (in_array('database', $enabled_modules)) {
        $user = self::findById(1);
      }
      else $user = new User();
    }
    return $user;
  }
  
  protected function hashPassword($password) {
    return md5($this->getSalt() . $password);
  }
  
  /**
   * if user is login or not
   * 
   * @return type
   */
  public function isLogin() {
    return $this->getEmail() == (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "SOME-THING-WON'T-MATCH");
  }
  
  public function login() {
    global $user;
    if ($this->getEmail()) {
      $_SESSION['user_email'] = $this->getEmail();
      $user = self::findByEmail($this->getEmail());
    } else {
      throw new Exception('Login error');
    }
  }
  
  public function logout() {
    global $user;
    
    unset($_SESSION['user_email']);
    unset($user);
  }
}