<?php

class FormWidget {
  private $confs;

  public function __construct($conf) {
    $this->conf = $conf;
  }
  
  public function render($module, $model) {}
  public function validate() {}
  public function proceed() {}
}