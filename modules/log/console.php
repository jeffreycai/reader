<?php

//-- Clear cache
if ($command == 'cc') {
  if ($arg1 == 'all') {
    echo " - Drop table 'log' ";
    echo Log::dropTable() ? "success\n" : "fail\n";
  }
}

//-- Import DB
if ($command == 'import' && $arg1 == 'db' && (is_null($arg2) || $arg2 == 'log') ) {
  //- create tables if not exits
  echo " - Create table 'log' ";
  echo Log::createTableIfNotExist() ? "success\n" : "fail\n";
}
