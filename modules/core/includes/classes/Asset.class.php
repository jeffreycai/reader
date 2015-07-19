<?php
class Asset {
  static $assets;
  
  private $path;
  private $position;
  private $weight;
  private $source;
  private $type;
  
  static function getTopAssets($type, $which_end) {
    $rtn = array();
    $keys = array();
    foreach(self::getAllAssets($which_end) as $asset) {
      if ($asset->position == 'top' && $asset->type == $type && preg_match('/'.$asset->path.'/', get_request_uri_relative())) {
        // check if already included
        if (in_array($asset->key, $keys)) {
          continue;
        }
        $keys[] = $asset->key;
        
        $rtn[] = $asset;
      }
    }
    return $rtn;
  }
  
  static function getBottomAssets($type, $which_end) {
    $rtn = array();
    $keys = array();
    foreach(self::getAllAssets($which_end) as $asset) {
      if ($asset->position == 'bottom' && $asset->type == $type) {
        // check if already included
        if (in_array($asset->key, $keys)) {
          continue;
        }
        $keys[] = $asset->key;
        
        $rtn[] = $asset;
      }
    }
    return $rtn;
  }
  
  static function getAllAssets($which_end) {
    // if cached already, return it
    if (isset(self::$assets)) {
      return self::$assets;
    }
    
    // otherwise, get it from settings
    $settings = Vars::getSettings();
    $assets = array();
    $i = 0;
    $indexes = array();
    foreach ($settings['assets'][$which_end] as $type => $asset_group) {
      foreach ($asset_group as $asset_key => $asset_attributes) {
        $asset = new Asset();
        $asset->type = $type;
        $asset->path = $asset_attributes['path'];
        $asset->source = $asset_attributes['source'];
        $asset->position = $asset_attributes['position'];
        $asset->weight = $asset_attributes['weight'];
        $asset->key = $asset_key;
        
        $assets[$i] = $asset;
        $indexes[$i] = $asset->weight;
        
        $i++;
      }
    }
    asort($indexes);
    $rtn = array();
    foreach ($indexes as $key => $val) {
      $rtn[] = $assets[$key];
    }
    return $rtn;
  }
  
  static function printTopAssets($which_end) {
    foreach (self::getTopAssets('css', $which_end) as $asset) {
      $url = preg_match('/^(\/\/|http)/', $asset->source) ? $asset->source : ('/' . get_sub_root() . 'modules/' . $asset->source);
      echo "  <link rel='stylesheet' href='$url'>\n";
    }
    foreach (self::getTopAssets('js', $which_end) as $asset) {
      $url = preg_match('/^(\/\/|http)/', $asset->source) ? $asset->source : ('/' . get_sub_root() . 'modules/' . $asset->source);
      echo "  <script type='text/javascript' src='$url'></script>\n";
    }
  }
  
  static function printBottomAssets($which_end) {
    foreach (self::getBottomAssets('css', $which_end) as $asset) {
      $url = preg_match('/^(\/\/|http)/', $asset->source) ? $asset->source : ('/' . get_sub_root() . 'modules/' . $asset->source);
      echo "  <link rel='stylesheet' href='$url'>\n";
    }
    foreach (self::getBottomAssets('js', $which_end) as $asset) {
      $url = preg_match('/^(\/\/|http)/', $asset->source) ? $asset->source : ('/' . get_sub_root() . 'modules/' . $asset->source);
      echo "  <script type='text/javascript' src='$url'></script>\n";
    }
  }
  
  static function checkAssetAdded($key, $type, $whichend) {
    foreach(self::getAllAssets($whichend) as $asset) {
      if ($asset->type == $type && $asset->key == $key) {
        return true;
      }
    }
    return self::checkAssetAddedDynamicly($key, $type, $whichend);
  }
  
  static function checkAssetAddedDynamicly($key, $type, $whichend) {
    global $dynamic_assets;
    if (!isset($dynamic_assets)) {
      $dynamic_assets = array();
    }
    if (!isset($dynamic_assets[$whichend])) {
      $dynamic_assets[$whichend] = array();
    }
    if (!isset($dynamic_assets[$whichend][$type])) {
      $dynamic_assets[$whichend][$type] = array();
    }
    return isset($dynamic_assets[$whichend][$type][$key]);
  }
  
  static function addDynamicAsset($key, $type, $whichend, $content) {
    global $dynamic_assets;
    if (!isset($dynamic_assets)) {
      $dynamic_assets = array();
    }
    if (!isset($dynamic_assets[$whichend])) {
      $dynamic_assets[$whichend] = array();
    }
    if (!isset($dynamic_assets[$whichend][$type])) {
      $dynamic_assets[$whichend][$type] = array();
    }
    $dynamic_assets[$whichend][$type][$key] = $content;
  }
  
  static function getDynamicAsset($key, $type, $whichend) {
    global $dynamic_assets;
    return $dynamic_assets[$whichend][$type][$key];
  }
  
  static function clearDynamicAssets() {
    global $dynamic_assets;
    $dynamic_assets = null;
    unset($dynamic_assets);
  }
  
  public function getKey() {
    return $this->key;
  }
}