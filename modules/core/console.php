<?php


// prod protection for cc all
if ($command == 'cc' && $arg1 == 'all' && ENV == 'prod') {
  echo "*********************************************************************\n";
  echo " !!! This site is now under PROD mode. ARE YOU SURE TO CLEAR ALL !!! \n";
  echo "*********************************************************************\n";
  echo "yes/no\n";
  $confirmation  =  trim( fgets( STDIN ) );
  if ( $confirmation !== 'yes' ) {
  echo "abort...\n";
  exit (0);
  }
}


//-- Clear cache
if ($command == 'cc') {
  if (Vars::tableExist() && $var = Vars::findByName('system')) {
    $var->delete();
    echo " - System var cache has been cleared.\n";
  }
  if ($arg1 == 'all') {
    echo " - Drop table 'vars' ";
    echo Vars::dropTable() ? "success\n" : "fail\n";
    
    echo " - Drop table 'user' ";
    echo User::dropTable() ? "success\n" : "fail\n";

  }
}

//-- Import DB
if ($command == 'import' && $arg1 == 'db' && (is_null($arg2) || $arg2 == 'core')) {
  echo " - Create table 'vars' ";
  echo Vars::createTableIfNotExist() ? "success\n" : "fail\n";
  
  echo " - Create table 'user' ";
  echo User::createTableIfNotExist() ? "success\n" : "fail\n";
}

//-- Import Fixture
if ($command == 'import' && $arg1 == 'fixture'  && (is_null($arg2) || $arg2 == 'user')) {
  User::importFixture();
  echo " - Import fixture for User\n";
}
if ($command == 'import' && $arg1 == 'fixture') {
  if (isset($arg2)) {
    import_fixture($arg2);
  } else {
    global $enabled_modules;
    foreach ($enabled_modules as $module) {
      import_fixture($module);
    }
  }
}


//-- Build Schemas
if ($command == 'build' && $arg1 == 'schemas') {
  
  if (isset($arg2)) {
    build_schemas($arg2);
  } else {
    global $enabled_modules;
    foreach ($enabled_modules as $module) {
      build_schemas($module);
    }
  }
}

//-- Build Form
if ($command == 'build' && $arg1 == 'form' && isset($arg2)) {
  $model = $arg2;
  $override = isset($arg3) && ($arg3 == "--force" || $arg3 == "-f");
  build_form($model, $override);
}


//-- Clone a module
if ($command == 'clone') {
  echo "We now start to create a new module from an existing module:\n";
  echo "Which module do you like to clone?\n";
  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  $from = trim($line);
  if (!module_exists($from)) {
    echo "Error: The module '$from' does not exist. \n";
    exit;
  }
  echo "What is the name your cloned new module will be? Please use single lower case word, e.g. 'product'\n";
  $line = fgets($handle);
  $to = trim($line);
  if (module_exists($to)) {
    echo "Error: the module '$to' already exist. \n";
    exit;
  }
  
  echo "\n ... Clone files ...\n\n";
  $result = shell_exec('rsync -azC --force ' . WEBROOT . DS . 'modules' . DS . $from . DS . ' ' . WEBROOT . DS . 'modules' . DS . $to);
  echo "Files cloned!\n";
  
  echo "\n ... Modify files ... \n\n";
  // loop over
  while (true) {
    echo "\n Enter the keyword you want to replace. e.g. 'blog', '日志': \n";
    echo "(enter 'n' if you want to exit)\n";
    $keyword = trim(fgets($handle));
    if ($keyword == 'n') {
      break;
    }
    echo "\n Enter the replacement word. e.g. 'product', '产品'";
    echo "(enter 'n' if you want to exit)\n";
    $replacement = trim(fgets($handle));
    if ($replacement == 'n') {
      break;
    }
    echo "\n ... Start replacing ...\n";
    loopReplacement($keyword, $replacement, WEBROOT . DS . 'modules' . DS . $to);
    echo "\n ... Keyword replace for '" . $keyword . "' => '" . $replacement . "' DONE!\n\n";
  }
}



function loopReplacement($keyword, $replacement, $path) {
  // for folder, recursive call
  if (is_dir($path)) {
    if ($handle = opendir($path)) {
      while (false !== ($entry = readdir($handle))) {
        // skip all entries start with "."
        if (!preg_match('/^\./', $entry)) {
          $next_path = $path . DS . $entry;
          loopReplacement($keyword, $replacement, $next_path);
        }
      }
      closedir($handle);
    }
  // for file, start replacement
  } else if (is_file($path)) {
    // file name replacement
    $name = basename($path);
    $dir = str_replace($name, '', $path);
    $name = str_replace($keyword, $replacement, $name);
    $name = str_replace(ucfirst($keyword), ucfirst($replacement), $name);
    $new_path = $dir . $name;
    rename($path, $new_path);
    
    // file content replacement
    $content = file_get_contents($new_path);
    $content = str_replace($keyword, $replacement, $content);
    $content = str_replace(ucfirst($keyword), ucfirst($replacement), $content);
    file_put_contents($new_path, $content);
  }
}