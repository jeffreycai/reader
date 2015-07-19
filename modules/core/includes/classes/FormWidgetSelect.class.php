<?php
require_once 'FormWidget.class.php';

class FormWidgetSelect extends FormWidget {
  private $name;
  private $required;
  private $options;
  
  public function __construct($name, $conf) {
    parent::__construct($conf);
    $this->name = $name;
    $this->required = isset($conf['required']) ? $conf['required'] : 0;
    $this->options = isset($conf['options']) ? $conf['options'] : array('0' => '-- Select --', 'val1' => 'option1', 'val2' => 'option2');
  }
  
  public function render($module, $model) {
    $options = "";
    foreach ($this->options as $key => $val) {
      $selected = '($object->isNew() ? ' . "(isset(\$_POST['$this->name']) ? (\$_POST['$this->name'] == '$key' ? 'selected=\"selected\"' : '') : '')" . ' : ($object->get' . format_as_class_name($this->name) . '() == "'.$key.'" ? "selected=\'selected\'" : ""))';
      $options .= "\n      <option value='$key' [[[ echo $selected ]]]>$val</option>";
    }
    
    $rtn = "";
    $rtn .=
"\n<div class='form-group'>
  <label>$this->name</label>
    <select class='form-control' id='$this->name' name='$this->name'>$options
    </select>
</div>
";
    return $rtn;
  }
  
  public function validate() {
    $rtn = "\n  // validation for $".$this->name."\n";
    $rtn .= '  $'.$this->name.' = isset($_POST["'.$this->name.'"]) ? strip_tags($_POST["'.$this->name.'"]) : null;';
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
    $rtn .= "  if (!empty($".$this->name.")) {\n";
    $rtn .= '    $object->set'.format_as_class_name($this->name).'($'.$this->name.');
';
    $rtn .= "  }\n";
    return $rtn;
  }
}