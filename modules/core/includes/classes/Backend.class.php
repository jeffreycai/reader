<?php
class Backend {
  static $top_nav = array();
  static $side_nav = array();
  static $dashboard = array();
  
  /**
   * Top nav registry
   */
  static public function registerTopNav($item, $weight = 0) {
    self::$top_nav[] = array('item' => $item, 'weight' => $weight);
  }
  
  static public function renderTopNavRegistry() {
    usort(self::$top_nav, build_int_sorter('weight'));
    $rtn = array();
    foreach (self::$top_nav as $item) {
      $rtn[] = $item['item'];
    }
    return implode('', $rtn);
  }
  
  /**
   * Side nav registry
   */
  static public function registerSideNav($item, $weight = 0) {
    self::$side_nav[] = array('item' => $item, 'weight' => $weight);
  }
  
  static public function renderSideNavRegistry() {
    usort(self::$side_nav, build_int_sorter('weight'));
    $rtn = array();
    foreach (self::$side_nav as $item) {
      $rtn[] = $item['item'];
    }
    return implode('', $rtn);
  }
  
  /**
   * Dashboard registry
   */
  static public function registerDashboard($item, $weight = 0) {
    self::$dashboard[] = array('item' => $item, 'weight' => $weight);
  }
  
  static public function renderDashboard() {
    usort(self::$dashboard, build_int_sorter('weight'));
    $rtn = array();
    foreach (self::$dashboard as $item) {
      $rtn[] = $item['item'];
    }
    return implode('', $rtn);
  }
}