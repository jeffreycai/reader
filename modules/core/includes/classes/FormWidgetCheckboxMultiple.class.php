<?php
require_once 'FormWidget.class.php';

class FormWidgetCheckboxMultiple extends FormWidget {
  private $name;
  private $required;
  private $options;
  
  public function __construct($name, $conf) {
    parent::__construct($conf);
    $this->name = $name;
    $this->required = isset($conf['required']) ? $conf['required'] : 0;
    $this->options = isset($conf['options']) ? $conf['options'] : array('val1' => 'option1', 'val2' => 'option2');
  }
  
  public function render($module, $model) {
    $options = "";
    foreach ($this->options as $key => $val) {
      $options .= "\n      <label><input type='checkbox' [[[ if (in_array('$val', \$items)): ]]]checked='checked'[[[ endif; ]]] name='".$this->name."[]' value='$key' /> $val</label>";
    }
    
    $rtn = "\n       [[[ \$items = (\$object->isNew() ? (isset(\$_POST['$this->name']) ? explode(';', \$_POST['$this->name']) : '') : explode(';', \$object->get". format_as_class_name($this->name)."() )) ]]]";
    $rtn .=
"\n<div class='form-group'>
  <label>$this->name</label>
    <div class='checkbox'>$options
    </div>
</div>
";
    return $rtn;
  }
  
  public function validate() {
    $rtn = "\n  // validation for $".$this->name."\n";
    $rtn .= '  $'.$this->name.' = isset($_POST["'.$this->name.'"]) ? $_POST["'.$this->name.'"] : null;';
    if ($this->required) {
      $rtn .= '
  if (empty($'.$this->name.')) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "'.$this->name.' is required.", "zh" => "请填写'.$this->name.'"))));
    $error_flag = true;
  }
';
    }
    return $rtn;
  }
  
  public function proceed() {
    $rtn = "\n  // proceed for $".$this->name."\n";
    $rtn .= '  $object->set'.format_as_class_name($this->name).'(implode(";", $'.$this->name.'));
';
    return $rtn;
  }
}