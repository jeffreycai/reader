<?php
include_once __DIR__ . DS . 'DBObject.class.php';

/**
 * DB fields
 * - name
 * - value
 */
class Vars extends DBObject {
  /**
   * Implement parent abstract functions
   */
  protected function setPrimaryKeyName() {
    $this->primary_key = array(
      'name'
    );
  }
  protected function setPrimaryKeyAutoIncreased() {
    $this->pk_auto_increased = FALSE;
  }
  protected function setTableName() {
    $this->table_name = 'vars';
  }
  
  /**
   * Setters and getters
   */
  public function setName($k) {
    $this->setDBFieldName($k);
  }
  public function getName() {
    return $this->getDbFieldName();
  }
  public function setValue($v) {
    $this->setDBFieldValue($v);
  }
  public function getValue() {
    return $this->getDbFieldValue();
  }
  
  /**
   * self functions
   */
  static $settings;
  
  static function dropTable() {
    return parent::dropTableByName('vars');
  }
  
  static function tableExist() {
    return parent::tableExistByName('vars');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
        CREATE  TABLE `vars` (
          `name` VARCHAR(30) NOT NULL ,
          `value` TEXT NOT NULL ,
          PRIMARY KEY (`name`) )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findByName($name) {
    global $mysqli;
    $query = 'SELECT * FROM vars WHERE name=' . DBObject::prepare_val_for_sql($name);
    $result = $mysqli->query($query);
    if ($result && $v = $result->fetch_object()) {
      $var = new Vars();
      DBObject::importQueryResultToDbObject($v, $var);
      return $var;
    }
    return null;
  }
  
  static function getSettings() {
    global $enabled_modules;
    $settings = false;
    
    // if already fetched, return it straight
    if (isset(self::$settings)) {
      return self::$settings;
    }

    // if table not exist, return null
    if (in_array('database', $enabled_modules)) {
      self::createTableIfNotExist();
      global $mysqli;
      // if not cached in db or in dev, load it
      $settings = self::findByName('system');
    }

    if (!$settings || ENV == 'dev') {
      require_once(MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'libraries' . DS . 'spyc' . DS . 'spyc.php');
      $settings = array();
      
      // load core module settings first
      $module_dir = MODULESROOT . DS . 'core';
      $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'settings.yml'), $settings);
      $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'routing.yml'), $settings);
      $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'role.yml'), $settings);

      // loop through modules and merge all settings
      if ($handle = opendir(MODULESROOT)) {
        while (false !== ($entry = readdir($handle))) {
          // skip any file starting with "."
          if (preg_match('/^\./', $entry)) {
            continue;
          }
          // loop over the module folder
          $module_dir = MODULESROOT . DS . $entry;
          if (is_dir($module_dir) && in_array($entry, $enabled_modules) && $entry != 'core') {
            if (is_file($module_dir . DS . 'settings.yml')) {
              $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'settings.yml'), $settings);
            }
            if (is_file($module_dir . DS . 'routing.yml')) {
              $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'routing.yml'), $settings);
            }
            if (is_file($module_dir . DS . 'role.yml')) {
              $settings = array_merge_recursive(Spyc::YAMLLoad($module_dir . DS . 'role.yml'), $settings);
            }
          }
        }
      }
      // store in db
      if (in_array('database', $enabled_modules) && ENV != 'dev') {
        $var = new Vars();
        $var->setName('system');
        $var->setValue(serialize($settings));
        $var->save();
        self::$settings = $settings;
      }
      return $settings;
    } else {
      self::$settings = unserialize($settings->getValue());
      return self::$settings;
    }
  }
  
  /**
   * return $settings in json format
   */
  static function renderSettingsInJson(Array $include) {
    $settings = Vars::getSettings();
    $rtn = array();

    foreach ($include as $key => $value)  {
      if (is_int($key) && isset($settings[$value])) {
        $rtn[$key] = $settings[$value];
      } else {
        $rtn[$key] = $value;
      }
    }
    
    return json_encode($rtn);
  }
}