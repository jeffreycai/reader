<?php
  //-- Queue:Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "queue") {
      echo " - Drop table 'queue' ";
      echo Queue::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- Queue:Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "queue") ) {
  //- create tables if not exits
  echo " - Create table 'queue' ";
  echo Queue::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  