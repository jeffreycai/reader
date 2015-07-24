<?php
  //-- WechatAccount:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "wechat_account") {
      echo " - Drop table 'wechat_account' ";
      echo WechatAccount::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- WechatAccount:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "wechat_account") ) {
  //- create tables if not exits
  echo " - Create table 'wechat_account' ";
  echo WechatAccount::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- WechatArticle:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "wechat_account") {
      echo " - Drop table 'wechat_article' ";
      echo WechatArticle::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- WechatArticle:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "wechat_article") ) {
  //- create tables if not exits
  echo " - Create table 'wechat_article' ";
  echo WechatArticle::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  
  //-- WechatAccountUser:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "wechat_account") {
      echo " - Drop table 'wechat_account_user' ";
      echo WechatAccountUser::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- WechatAccountUser:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "wechat_account_user") ) {
  //- create tables if not exits
  echo " - Create table 'wechat_account_user' ";
  echo WechatAccountUser::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  