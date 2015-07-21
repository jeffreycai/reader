<?php
/**
 * How to use this class: (take 'Staff.class.php' as an example)
 * - create a sub class extending DBObject
 * - define setPrimaryKeyName() and setTableName() to assign the name of primary key and table
 * - create stubs functions to set/get db fields
 * - example to update/create a db record:
 *      $staff = new Staff(); 
 *      $staff->setAgency('state transit'); // set primary key
 *      $staff->setCN('544662');
 *      $staff->setIsAbused(1);
 *      ...
 *      $staff->save();
 * 
 */
abstract class DBObject {
  
  protected $primary_key;
  protected $table_name;
  protected $pk_auto_increased;
  
  abstract protected function setPrimaryKeyName(); // function to assign "primary key" field name to $this->primary_key
  abstract protected function setTableName(); // function to assign "table_name" field name to $this->table_name
  protected function setPrimaryKeyAutoIncreased() {
    $this->pk_auto_increased = false; // by default, we assume pk is not auto-increased. You sub class table has an auto-increased pk, it needs to override this method to set it to true
  }
  protected function getPrimaryKeyAutoIncreased() {
    return $this->pk_auto_increased;
  }
  protected function getPrimaryKeyName() {
    return $this->primary_key;
  }
  protected function getTableName() {
    return $this->table_name;
  }

  protected $last_execute_query;

  /**
   * Each sub-class needs to assign his "primary key" and "table name"
   */
  public function __construct() {
    $this->setPrimaryKeyName();
    $this->setPrimaryKeyAutoIncreased();
    $this->setTableName();
  }

  /**
   * PHP Magic function __call()
   * 
   * @param type $name
   * @return null
   */
  public function __call($name , $arguments) {
    // method "setDbFieldXxxx()" to set a db field
    $matches = array();
    if (preg_match('/^setDbField(.+)/i', $name, $matches)) {
      $field = strtolower($matches[1]);
      $this->{'db_field_' . $field} = $arguments[0]; // all db fields start with prefix "db_field_"
    // method "getDbFieldXxxx() to get a db field
    } else if (preg_match('/getDbField(.+)/i', $name, $matches)) {
      $field = strtolower($matches[1]);
      $field = 'db_field_' . $field;  // all db fields start with prefix "db_field_"
      return @(isset($this->{$field}) ? $this->{$field} : null);
    }
  }
  
  /**
   * Store db fields to database
   * Note: db field var name starts with "db_field_"
   */
  public function save() {
    $is_update = $this->checkExisted();
    
    // do db update
    global $mysqli;
    $query;
    
    
    //*-- if update, do update
    if ($is_update) {
      $query = "UPDATE `" . $this->getTableName() . "` SET ";
      
      // set fields
      $set_clauses = array();
      foreach ($this->getDbFields() as $field => $val) {
        // skip primary keys
        if (is_array($this->getPrimaryKeyName())) {
          if (in_array($field, $this->getPrimaryKeyName())) {
            continue;
          }
        } else {
          if ($field == $this->getPrimaryKeyName()) {
            continue;
          }
        }
        // --
        $set_clauses[] = "`$field`=" . self::prepare_val_for_sql($val) ;
      }
      $query .= implode(', ', $set_clauses);
      
      // where clause
      if (is_array($this->getPrimaryKeyName())) {
        $where = array();
        foreach ($this->getPrimaryKeyName() as $field) {
          $val = $this->{'getDfField' . $field}();
          $where[] = "`$field`=" . self::prepare_val_for_sql($this->{'db_field_' . $field});
        }
        $query .= " WHERE " . implode(' AND ', $where);
      } else {
        $val = $this->{'db_field_' . $this->getPrimaryKeyName()};
        $query .= " WHERE " . "`" . $this->getPrimaryKeyName() . "`=" . self::prepare_val_for_sql($val);
      }
      
      $query .= ";";
    //*-- if not update, create a new record
    } else {
      $query = "INSERT INTO `" . $this->getTableName() . "`";
      $insert_fields = array();
      $insert_vals = array();
      foreach ($this->getDbFields() as $field => $val) {
        $insert_fields[] = "`$field`";
        $insert_vals[] = self::prepare_val_for_sql($val);
      }
      $query .= " (" . implode(',', $insert_fields) . ") VALUES (" . implode(',', $insert_vals) . ");";
    }
//if ($this->getTableName() == 'user_5_account') {
//  die($query);
//}
//die("<meta charset='utf-8'>".$query);
    $result = $mysqli->query($query);
    $this->last_execute_query = $query;

    // update auto increase id as primary key
    if (!$is_update && sizeof($this->primary_key) == 1 && $this->pk_auto_increased) {
      $id = array_pop($this->primary_key);
      $this->{"db_field_" . $id} = $mysqli->insert_id;
    }
    
    // update auto increase id, but when it is not primary key
    if (!$is_update && !in_array('id', $this->primary_key) && $this->pk_auto_increased) {
      $id = array_pop($this->primary_key);
      $this->{"db_field_" . $id} = $mysqli->insert_id;
    }
    return $result;
  }
  
  /**
   * check if a db record exists
   * 
   * @global type $mysqli
   * @return type
   * @throws Exception
   */
  public function checkExisted() {
    global $mysqli;

    // if primary key is an array, make sure that every field is set, otherwise, it does not exist
    if (is_array($this->getPrimaryKeyName())) {
      foreach ($this->getPrimaryKeyName() as $pk_field) {
        if (!isset($this->{'db_field_' . $pk_field})) {
          return false;
        }
      }
    // if primary key is a single field, check if it is set, otherwise, it does not exist
    } else {
      if (!isset($this->{'db_field_' . $this->getPrimaryKeyName()})) {
        return false;
      }
    }
    
    $query = "SELECT * FROM `" . $this->getTableName() . "` WHERE ";
    if (is_array($this->getPrimaryKeyName())) {
      $where_clause = array();
      foreach ($this->getPrimaryKeyName() as $pk_field) {
        $where_clause[] = "`$pk_field`=" . self::prepare_val_for_sql($this->{'db_field_' . $pk_field});
      }
      $query .= implode(' AND ', $where_clause);
    } else {
      $query .= "`" . $this->getPrimaryKeyName() . "`=" . self::prepare_val_for_sql($this->{'db_field_' . $this->getPrimaryKeyName()});
    }
    $query .= ";";

    $result = $mysqli->query($query);
    $this->last_execute_query = $query;
    
    if ($result !== false && $result->num_rows != 0) {
      return true;
    }
    return false;
  }
  
  /**
   * get all db fields key => val in an array
   * 
   * @return type
   */
  public function getDbFields() {
    $rtn = array();
    foreach (get_object_vars($this) as $key => $val) {
      if (preg_match('/^db_field_/', $key)) {
        $db_field = str_replace('db_field_', '', $key);
        $rtn[$db_field] = $val;
      }      
    }
    return $rtn;
  }
  
  public function delete() {
    global $mysqli;
    $query = "DELETE FROM `$this->table_name` WHERE ";
    
    $tokens = array();
    foreach ($this->primary_key as $key) {
      $tokens[] = "`$key`=" . self::prepare_val_for_sql($this->{"getDbField" . ucfirst($key)}());
    }
    $query .= implode(' AND ', $tokens);
    $this->last_execute_query = $query;

    return $mysqli->query($query);
  }
  
  public function isNew() {
    $rtn = true;

    foreach ($this->primary_key as $field) {
      $val = $this->{"get".self::tableNameToClassName($field)}();
      $rtn &= is_null($val);
    }
 
    return $rtn;
  }
  
  public function getLastExecuteQuery() {
    return $this->last_execute_query;
  }
  
  /**
   * prepare a value for sql statuement
   * 
   * @param type $val
   * @return string
   */
  static function prepare_val_for_sql($val) {
    global $mysqli;
    if (is_string($val)) {
      return "'" . mysqli_escape_string($mysqli, $val) . "'";
    } else if (is_null($val)) {
      return 'NULL';
    } else {
      return $val;
    }
  }
  
  /**
   * import mysqli result to a db object
   * 
   * @param stdClass $result
   * @param DBObject $object
   */
  static function importQueryResultToDbObject(stdClass &$result, DBObject &$object) {
    foreach (get_object_vars($result) as $key => $val) {
      $object->{'db_field_' . strtolower($key)} = $val;
    }
  }
  
  /**
   * convert a table name to cameral case class name
   * 
   * @param type $table_name
   */
  static function tableNameToClassName($table_name) {
    $tokens = explode('_', $table_name);
    $class_name = '';
    for ($i = 0; $i < sizeof($tokens); $i++) {
      $token = ucfirst($tokens[$i]);
      $class_name .= $token;
    }
    return $class_name;
  }
  
  /**
   * Drop a db table
   * 
   * @global type $mysqli
   * @param type $name
   * @return boolean
   */
  static function dropTableByName($name) {
    global $mysqli;
    if (self::tableExistByName($name)) {
      return $mysqli->query('drop table `'.$name.'`;');
    }
    return true;
  }
  
  /**
   * Check if a table exist
   * 
   * @global type $mysqli
   * @param type $name
   * @return type
   */
  static function tableExistByName($name) {
    global $mysqli;
    $result = $mysqli->query('SHOW TABLES LIKE \''.$name.'\'');
    return $result->num_rows != 0;
  }
}
