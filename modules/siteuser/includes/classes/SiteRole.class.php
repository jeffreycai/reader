<?php
require_once "BaseSiteRole.class.php";

class SiteRole extends BaseSiteRole {
  public function hasPermission($name) {
    global $mysqli;
    $permission = SitePermission::findByName($name);
    if ($permission) {
      $query = "SELECT * FROM site_permission_role WHERE permission_id=" . $permission->getId() . " AND role_id=" . $this->getId();
      if ($mysqli->query($query)->fetch_object()) {
        return true;
      }
    }

    return false;
  }
  
  public function getPermissions() {
    global $mysqli;
    $query = "SELECT P.* FROM site_permission_role AS PR, site_permission AS P WHERE P.id=PR.permission_id AND PR.role_id=" . $this->getid();
    $result = $mysqli->query($query);
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj = new SitePermission();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    return $rtn;
  }
}
