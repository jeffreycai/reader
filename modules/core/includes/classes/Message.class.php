<?php
class Message {
  protected $type;
  protected $content;
  protected $data;
  
  const PRIMARY = 'primary';
  const SUCCESS = 'success';
  const INFO = 'info';
  const WARNING = 'warning';
  const DANGER = 'danger';
  
  const _SESSION_KEY_ = '_Messages_';


  public function __construct($type, $content) {
    $this->setType($type);
    $this->setContent($content);
  }

  /**
   * Setters and Getters
   */
  public function setType($type) {
    $this->type = $type;
  }
  public function getType() {
    return $this->type;
  }
  public function setContent($c) {
    $this->content = $c;
  }
  public function getContent() {
    return $this->content;
  }
  public function setData($d) {
    $this->data = $d;
  }
  public function getData() {
    return $this->data;
  }
  
  /**
   * Other functions
   */
  static function register($m) {
    if (!isset($_SESSION[self::_SESSION_KEY_]) || !is_array($_SESSION[self::_SESSION_KEY_])) {
      $_SESSION[self::_SESSION_KEY_] = array();
    }
    
    if (is_array($m)) {
      foreach ($m as $message) {
        array_push($_SESSION[self::_SESSION_KEY_], $message);
      }
    } else {
      array_push($_SESSION[self::_SESSION_KEY_], $m);
    }
  }
  
  static function getMessages($type = null) {
    $rtn = array();
    if (isset($_SESSION[self::_SESSION_KEY_]) && is_array($_SESSION[self::_SESSION_KEY_])) {
      $total = sizeof($_SESSION[self::_SESSION_KEY_]);
      for ($i = 0; $i < $total; $i++) {
        $message = $_SESSION[self::_SESSION_KEY_][$i];
        if ($type) {
          if ($message->getType() == $type) {
            if (!isset($rtn[$message->getType()]) || !is_array($rtn[$message->getType()])) {
              $rtn[$message->getType()] = array();
            }
            $rtn[$message->getType()][] = $message;
            unset($_SESSION[self::_SESSION_KEY_][$i]);
          }
//          $_SESSION[self::_SESSION_KEY_] = array_values($_SESSION[self::_SESSION_KEY_]);
        } else {
          if (!isset($rtn[$message->getType()]) || !is_array($rtn[$message->getType()])) {
            $rtn[$message->getType()] = array();
          }
          $rtn[$message->getType()][] = $message;
          unset($_SESSION[self::_SESSION_KEY_][$i]);
//          $_SESSION[self::_SESSION_KEY_] = array_values($_SESSION[self::_SESSION_KEY_]);
        }
      }
    }

    return $rtn;
  }
  
  static function renderMessages($type = null) {
    $rtn = '';
    foreach (self::getMessages() as $type => $messages) {
      $rtn .= "<div id='msg' class='alert alert-$type'>";
      
      if (sizeof($messages) == 1) {
        $rtn .= $messages[0]->getContent();
      } else {
        $rtn .= "<ul>";
        $msgs = array();
        foreach ($messages as $msg) {
          $msgs[] = "<li>" . $msg->getContent() . "</li>";
        }
        $rtn .= implode('', $msgs);
        $rtn .= "</ul>";
      }
      $rtn .= "</div>";
    }
    return $rtn;
  }
}