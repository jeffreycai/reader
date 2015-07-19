include_once MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'DBObject.class.php';

/**
 * DB fields
<?php foreach ($fields as $key => $field): ?>
 * - <?php echo $key . "\n" ?>
<?php endforeach; ?>
 */
class Base<?php echo $class ?> extends DBObject {
  /**
   * Implement parent abstract functions
   */
  protected function setPrimaryKeyName() {
    $this->primary_key = array(
      '<?php echo $pk ?>'
    );
  }
  protected function setPrimaryKeyAutoIncreased() {
    $this->pk_auto_increased = TRUE;
  }
  protected function setTableName() {
    $this->table_name = '<?php echo $table; ?>';
  }
  
  /**
   * Setters and getters
   */
<?php foreach (array_keys($fields) as $field): ?>
   public function set<?php echo format_as_class_name($field) ?>($var) {
     $this->setDbField<?php echo ucfirst($field) ?>($var);
   }
   public function get<?php echo format_as_class_name($field) ?>() {
     return $this->getDbField<?php echo ucfirst($field) ?>();
   }
<?php
/** 
 * for i18n fields 
 */
if (preg_match('/_en$/', $field)): 
  $tokens = explode('_', $field);
  $lang = array_pop($tokens);
  $base_field = implode('_', $tokens);
  $base_field_class_name = format_as_class_name($base_field);
?>
  public function set<?php echo $base_field_class_name ?>($var, $lang = null) {
    $lang = is_null($lang) ? get_language() : $lang;
    
    $method = "set<?php echo $base_field_class_name ?>" . ucfirst($lang);
    $this->{$method}($var);
  }
  public function get<?php echo $base_field_class_name ?>($lang = null) {
    $lang = is_null($lang) ? get_language() : $lang;
    
    $method = "get<?php echo $base_field_class_name ?>" . ucfirst($lang);
    return $this->{$method}();
  }
<?php endif; ?>
<?php endforeach; ?>

  
  
  /**
   * self functions
   */
  static function dropTable() {
    return parent::dropTableByName('<?php echo $table ?>');
  }
  
  static function tableExist() {
    return parent::tableExistByName('<?php echo $table ?>');
  }
  
  static function createTableIfNotExist() {
    global $mysqli;

    if (!self::tableExist()) {
      return $mysqli->query('
CREATE TABLE IF NOT EXISTS `<?php echo $table ?>` (
<?php foreach ($fields as $key => $val): ?>
  `<?php echo $key ?>` <?php echo $val ?> ,
<?php endforeach; ?>
  PRIMARY KEY (`<?php echo $pk ?>`)
<?php if ($indexes): ?> ,
<?php for ($i = 0; $i < sizeof($indexes); $i++): $index = $indexes[$i]; ?>
INDEX <?php echo $index ?><?php if ($i < sizeof($indexes) - 1): ?> ,
<?php endif; ?>
<?php endfor; ?>
<?php endif; ?>
<?php if ($fks): $i = -1;?> ,
<?php foreach ($fks as $fk_name => $fk_settings): $i++; $tokens = explode('.', $fk_settings['references']); $reference_table = $tokens[0]; $reference_field = $tokens[1]; ?>
INDEX `<?php echo "fk-$table-$fk_name-idx" ?>` (`<?php echo $fk_settings['foreign_key'] ?>` ASC),
CONSTRAINT `<?php echo "fk-$table-$fk_name" ?>`
  FOREIGN KEY (`<?php echo $fk_settings['foreign_key'] ?>`)
  REFERENCES `<?php echo $reference_table ?>` (`<?php echo $reference_field ?>`)
  ON DELETE CASCADE
  ON UPDATE CASCADE<?php if ($i < sizeof($fks) - 1): ?> ,
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
      ');
    }
    
    return true;
  }
  
  static function findById($id, $instance = '<?php echo $class ?>') {
    global $mysqli;
    $query = 'SELECT * FROM <?php echo $table ?> WHERE id=' . $id;
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
    $query = "SELECT * FROM <?php echo $table ?>";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new <?php echo $class ?>();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function findAllWithPage($page, $entries_per_page) {
    global $mysqli;
    $query = "SELECT * FROM <?php echo $table ?> LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new <?php echo $class ?>();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function countAll() {
    global $mysqli;
    $query = "SELECT COUNT(*) as 'count' FROM <?php echo $table ?>";
    if ($result = $mysqli->query($query)) {
      return $result->fetch_object()->count;
    }
  }
  
  static function truncate() {
    global $mysqli;
    $query = "TRUNCATE TABLE <?php echo $table; ?>";
    return $mysqli->query($query);
  }
}