<?php
  //-- User1Account:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser_wechat_dummy") {
      echo " - Drop table 'user_1_account' ";
      echo User1Account::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- User1Account:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "user_1_account") ) {
  //- create tables if not exits
  echo " - Create table 'user_1_account' ";
  echo User1Account::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- User1Category:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser_wechat_dummy") {
      echo " - Drop table 'user_1_category' ";
      echo User1Category::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- User1Category:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "user_1_category") ) {
  //- create tables if not exits
  echo " - Create table 'user_1_category' ";
  echo User1Category::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- User1Read:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "siteuser_wechat_dummy") {
      echo " - Drop table 'user_1_read' ";
      echo User1Read::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- User1Read:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "user_1_read") ) {
  //- create tables if not exits
  echo " - Create table 'user_1_read' ";
  echo User1Read::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  