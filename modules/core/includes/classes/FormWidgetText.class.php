<?php
require_once 'FormWidget.class.php';

class FormWidgetText extends FormWidget {
  private $name;
  private $required;
  private $size;
  
  public function __construct($name, $conf) {
    parent::__construct($conf);
    $this->name = $name;
    $this->required = isset($conf['required']) ? $conf['required'] : 0;
    $this->size = isset($conf['size']) ? $conf['size'] : false;
  }
  
  public function render($module, $model) {
    $rtn = "";
    $prepopulate = '($object->isNew() ? ' . "(isset(\$_POST['$this->name']) ? strip_tags(\$_POST['$this->name']) : '')" . ' : $object->get' . format_as_class_name($this->name) . '())';
    $rtn .=
"\n<div class='form-group'>
  <label for='$this->name'>$this->name</label>
  <input value='[[[ echo htmlentities(str_replace('\'', '\"', $prepopulate)) ]]]' type='text' class='form-control' id='$this->name' name='$this->name'".($this->required ? ' required' : '').($this->size ? ' size='.$this->size : '')." />
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
    if ($this->size) {
      $rtn .= '
  if (strlen($'.$this->name.') >= '.$this->size.') {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "Max length for '.$this->name.' is '.$this->size.'", "zh" => "'.$this->name.' 不能超过'.$this->size.'个字符"))));
    $error_flag = true;
  }
';
    }
    return $rtn;
  }
  
  public function proceed() {
    $rtn = "\n  // proceed for $".$this->name."\n";
    $rtn .= '  $object->set'.format_as_class_name($this->name).'($'.$this->name.');
';
    return $rtn;
  }
}