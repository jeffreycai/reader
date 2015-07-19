<?php
  //-- SiteProfile:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser_profile") {
      echo " - Drop table 'site_profile' ";
      echo SiteProfile::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- SiteProfile:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "site_profile") ) {
  //- create tables if not exits
  echo " - Create table 'site_profile' ";
  echo SiteProfile::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  