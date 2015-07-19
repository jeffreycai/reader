<?php
  //-- SiteUser:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser") {
      echo " - Drop table 'site_user' ";
      echo SiteUser::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SiteUser:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_user") ) {
  //- create tables if not exits
  echo " - Create table 'site_user' ";
  echo SiteUser::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- SitePermission:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser") {
      echo " - Drop table 'site_permission' ";
      echo SitePermission::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SitePermission:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_permission") ) {
  //- create tables if not exits
  echo " - Create table 'site_permission' ";
  echo SitePermission::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- SiteRole:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser") {
      echo " - Drop table 'site_role' ";
      echo SiteRole::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SiteRole:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_role") ) {
  //- create tables if not exits
  echo " - Create table 'site_role' ";
  echo SiteRole::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- SitePermissionRole:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser") {
      echo " - Drop table 'site_permission_role' ";
      echo SitePermissionRole::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SitePermissionRole:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_permission_role") ) {
  //- create tables if not exits
  echo " - Create table 'site_permission_role' ";
  echo SitePermissionRole::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- SiteUserRole:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser") {
      echo " - Drop table 'site_user_role' ";
      echo SiteUserRole::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SiteUserRole:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_user_role") ) {
  //- create tables if not exits
  echo " - Create table 'site_user_role' ";
  echo SiteUserRole::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  