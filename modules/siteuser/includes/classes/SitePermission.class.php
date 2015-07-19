<?php
require_once "BaseSitePermission.class.php";

class SitePermission extends BaseSitePermission {
  public static function findByName($name) {
    global $mysqli;
    $query = 'SELECT * FROM site_permission WHERE name=' . DBObject::prepare_val_for_sql($name);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new SitePermission();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
}
