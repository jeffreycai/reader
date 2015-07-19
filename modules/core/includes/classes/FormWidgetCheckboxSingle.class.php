<?php
require_once 'FormWidget.class.php';

class FormWidgetCheckboxSingle extends FormWidget {
  private $name;
  
  public function __construct($name, $conf) {
    parent::__construct($conf);
    $this->name = $name;
  }
  
  public function render($module, $model) {
    $rtn = "";
    $checked = '($object->isNew() ? ' . "(isset(\$_POST['$this->name']) ? (\$_POST['$this->name'] ? 'checked=\"checked\"' : '') : '')" . ' : ($object->get' . format_as_class_name($this->name) . '() ? "checked=\'checked\'" : ""))';
    $rtn .=
"\n<div class='checkbox'>
  <label>
    <input type='checkbox' [[[ echo $checked ]]] id='$this->name' name='$this->name' value='1' /> $this->name
  </label>
</div>
";
    return $rtn;
  }
  
  public function validate() {
    $rtn = "\n  // validation for $".$this->name."\n";
    $rtn .= '  $'.$this->name.' = isset($_POST["'.$this->name.'"]) ? 1 : 0;';
    return $rtn;
  }
  
  public function proceed() {
    $rtn = "\n  // proceed for $".$this->name."\n";
    $rtn .= '  $object->set'.format_as_class_name($this->name).'($'.$this->name.');
';
    return $rtn;
  }
}