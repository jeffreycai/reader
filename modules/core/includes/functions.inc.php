<?php
/**
 * Auto-load register
 * 
 * @param type $class
 */
function custom_loader($class) {
  global $enabled_modules;
  
  foreach ($enabled_modules as $module) {
    // loop over the module folder
    $module_dir = MODULESROOT . DS . $module;
    if (is_dir($module_dir)) {
      $class_dir = $module_dir . DS . 'includes' . DS . 'classes';
      include_class_file($class_dir, $class);
    }
  }
}
function include_class_file($dir, $class) {
  if (is_dir($dir) && $handle = opendir($dir)) {
    while (false !== ($entry = readdir($handle))) {
      // skip any file starting with "."
      if (preg_match('/^\./', $entry)) {
        continue;
      }
      // loop over
      $target = $dir . DS . $entry;
      if (is_file($target) && $entry == $class . '.class.php') {
        include_once($target);
      } else if (is_dir($target)) {
        include_class_file($target, $class);
      }
    }
  }
}

/**
 * Dispatch to a controller
 * 
 * @param type $controller
 */
function dispatch($controller, $vars = array()) {
  $tokens = explode('/', $controller);
  $module = array_shift($tokens);
  $path = implode('/', $tokens);
  
  $controller_file = MODULESROOT . DS . $module . DS . 'controllers' . DS . $path . ".php";
  if (is_file($controller_file)) {
    foreach ($vars as $key => $val) {
      $$key = $val;
    }
    $settings = Vars::getSettings();
    require_once $controller_file;
  } else {
    die("Controller '$controller' does not exist.");
  }
}


/**
 * Print out a var
 * 
 * @param type $var
 * @param type $html
 */
function _debug($var, $html = true) {
  if ($html) {
    echo "<pre>";
  }
  var_dump($var);
  if ($html) {
    echo "</pre>";
  }
  die();
}

/**
* Build GET query string 
*/
function build_query_string($params) {
  $rtn = array();
  foreach ($params as $key => $val) {
    if (empty($val)) {
      continue;
    }
    
    $key = urlencode(strip_tags($key));
    $val = urlencode(strip_tags($val));
    $rtn[] = $key.'='.$val;
  }
  return '?'.implode('&', $rtn);
}

/**
* Update a $_REQUEST parameter value and output the query string
*/
function update_query_string($input) {
  $url = explode('?', get_cur_page_url()); 
  $url = $url[0];
  $params = $_GET;
  foreach ($input as $key => $val) {
    $params[$key] = $val;
  }
  return $url . build_query_string($params);
}

/**
* Get current page url
*/
function get_cur_page_url($with_domain = false) {
  global $cur_page_url;
  if (isset($cur_page_url)) {
    return $cur_page_url;
  }
  
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 if (!$with_domain) {
   return preg_replace('/^https?:\/\/[^\/]+/', '', $pageURL);
 }
 $cur_page_url = $pageURL;
 return $cur_page_url;
}

/**
 * echo "active" class for a link according to the current page url
 * 
 * @param type $active_url
 * @param type $current_url
 * @param type $class
 */
function echo_link_active_class($pattern, $current_url, $class='active') {
  // we strip the language prefix first
  if (preg_match('/^\/[^\.][^\.]\//', $current_url)) {
    $current_url = substr($current_url, 3);
  }
  $current_url = trim($current_url, "/");
  if (preg_match($pattern, $current_url)) {
    echo " class='$class' ";
  }
}

/**
 * convert a time stamp to time ago
 * @return string
 */
function time_ago($start_point, $end_point = null) {
  if ($end_point == null) {
    $end_point = time();
  }
  
  $etime = $end_point - $start_point;
  if ($etime < 1) {
    return '0 seconds';
  }
  $a = array( 12 * 30 * 24 * 60 * 60  =>  '年',
              30 * 24 * 60 * 60       =>  '月',
              24 * 60 * 60            =>  '日',
              60 * 60                 =>  '小时',
              60                      =>  '分钟',
              1                       =>  '秒'
  );

  foreach ($a as $secs => $str) {
    $d = $etime / $secs;
    if ($d >= 1)
    {
      $r = round($d);
//      return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
      return $r . ' ' . $str . '之前';
    }
  }
}

function load_library_wide_image() {
  require_once  WEBROOT . DS . 'modules' . DS . 'core' . DS . 'includes' . DS . 'libraries' . DS . 'wideimage' . DS . 'lib' . DS . 'WideImage.php';
}

function resetSpamTokens() {
  $_SESSION['spam_key'] = generateRandomChars();
  $_SESSION['spam_val'] = generateRandomChars();
}
function getSpamKey() {
  return $_SESSION['spam_key'];
}
function getSpamVal() {
  return $_SESSION['spam_val'];
}

function html_to_text($html) {
  $rtn = preg_replace('/<\/?p([^>]+)?>/', '', $html);
  $rtn = preg_replace('/<\/?a([^>]+)?>/', '', $rtn);
  $rtn = preg_replace('/<img[^>]+\/?>/', '', $rtn);
  $rtn = preg_replace('/<br ?\/?>/', "\n", $rtn);
  $rtn = preg_replace('/<\/?strong>/', '', $rtn);
  $rtn = preg_replace('/<\/?blockquote>/', '', $rtn);

  
  $rtn = preg_replace('/<\/?[u|o]l>/', '', $rtn);
  $rtn = preg_replace('/<li>/', '- ', $rtn);
  $rtn = preg_replace('/<\/li>/', '', $rtn);
  return $rtn;
}

/**
 * return subroot
 * 
 * @global type $sub_root
 * @return string
 */
function get_sub_root() {
  // don't calculate if already done
  if (isset($_SESSION['sub_root'])) {
    return $_SESSION['sub_root'];
  }
  
  $uri = get_request_uri();
  
  $uri_tokens = explode('/', trim($uri, '/'));
  $path_tokens = explode(DS, trim(WEBROOT, DS));

  $sub_root = "";
  $pointer = null;
  while ($token = array_shift($uri_tokens)) {
    for ($i = 0; $i < sizeof($path_tokens); $i++) {
      $p_token = $path_tokens[$i];
      // when it hits the first occurance
      if (is_null($pointer) && $p_token == $token) {
        $pointer = $i;
        $sub_root = $p_token;
      } else if (!is_null($pointer) && $i > 0 && $token == $path_tokens[$i - 1]) {
        $pointer = $i - 1;
        $sub_root = $path_tokens[$pointer] . '/' . $sub_root;
      }
    }
  }
  if ($sub_root != '') {
    $sub_root .= '/';
  }
  $_SESSION['sub_root'] = $sub_root;
  return $sub_root;
}

/**
 * Get query string
 * 
 * @return type
 */
function get_query_string() {
  return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
}

/**
 * Get Request uri
 * 
 * @param type $no_query_string
 * @return type
 */
function get_request_uri($no_query_string = true) {
  $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
  if ($no_query_string) {
    $request_uri = str_replace('?' . get_query_string(), '', $request_uri);
  }
  return $request_uri;
}

/**
 * Get Request uri without subroot and lang code
 * 
 * @return type
 */
function get_request_uri_relative($no_query_string = true) {
  $request_uri = get_request_uri($no_query_string);
  return preg_replace('/^\/' . str_replace('/', '\/', get_sub_root()) . '(' . get_language() . '(\/|$))?/', '/', $request_uri);
}

/**
 * Return the user preferred language
 */
function get_language() {
  $settings = Vars::getSettings();
  
  // when i18n is off, we take the default language
  if (isset($settings['i18n_lang_default'])) {
    $_SESSION['lang'] = $settings['i18n_lang_default'];
    return $_SESSION['lang'];
  }
  
  // first we check if the lang is in url
  $request_uri = get_request_uri();
  $uri_without_subroot = str_replace(get_sub_root(), '', $request_uri);
  $tokens = explode('/', trim($uri_without_subroot, '/'));
  $lang = array_shift($tokens);
  if (isset($settings['i18n_lang']) && in_array($lang, array_keys($settings['i18n_lang']))) {
    $_SESSION['lang'] = $lang;
    return $lang;
  }
  // if not, we check if it is in session
  if (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
    return $_SESSION['lang'];
  }
  // if not either, we get it from $_SERVER['HTTP_ACCEPT_LANGUAGE']
  $lang = substr(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '', 0, 2);
  if (isset($settings['i18n_lang']) && in_array($lang, array_keys($settings['i18n_lang']))) {
    $_SESSION['lang'] = $lang;
    return $lang;
  }
  // finaly, just return 'en'
  $_SESSION['lang'] = 'en';
  $lang = 'en';
  return $lang;
}

/**
 * return the lang code from url, if none exist, return false
 */
function get_language_from_url() {
  $request_uri = get_request_uri();
  $string = str_replace(get_sub_root(), '', $request_uri);
}

function i18n(Array $lang) {
  return $lang[get_language()];
}

function i18n_echo(Array $lang) {
  echo i18n($lang);
}

/**
 * print out an uri
 */
function uri($uri, $i18n = true) {
  $settings = Vars::getSettings();
  $rtn = "/" . get_sub_root();
  if (isset($settings['i18n']) && $settings['i18n'] && $i18n) {
    $rtn .= get_language() . '/';
  }
  $rtn .= $uri;
  
  return $rtn;
}

function print_uri($uri, $i18n = true) {
  echo uri($uri, $i18n);
}

/**
 * get a random string
 * 
 * @param type $length
 * @return type
 */
function get_random_string($length = 10, $str_base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
  $characters = $str_base;
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

/**
 * custom sort function for "usort()"
 * 
 * @param type $key, the sort key
 * @return type
 */
function build_int_sorter($key) {
  return function ($a, $b) use ($key) {
    if (is_array($a) && is_array($b)) {
      // if key is not defined, we just return 999999
      if (!isset($a[$key]) || !isset($b[$key])) {
        return 0;
      }
      return $a[$key] - $b[$key];
    } else if (is_object($a) && is_object($b)) {
      // if key is not defined, we just return 0
      if (!isset($a->$key) || !isset($b->$key)) {
        return 0;
      }
      return $a->$key - $b->$key;
    }
  };
}

/**
 * Check if a controller exists or not
 * 
 * @param type $controller
 * @return type
 */
function controllerExists($controller) {
  $tokens = explode('/', $controller);
  $module = array_shift($tokens);
  $controller = implode('/', $tokens);
  return is_file(MODULESROOT . DS . $module . DS . 'controllers' . DS . $controller . '.php');
}

/**
 * Load a module .yml fixture file
 * 
 * @param type $module
 * @param type $fixture_file
 * @return type
 */
function load_fixture($module, $fixture_file = 'fixture.yml') {
  require_once(MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'libraries' . DS . 'spyc' . DS . 'spyc.php');
  
  $file = MODULESROOT . DS . $module . DS . $fixture_file;
  if (is_file($file)) {
    return Spyc::YAMLLoad($file);
  }
}

/**
 * Import module fixture.yml
 */
function import_fixture($module) {
  $module_path = MODULESROOT . DS . $module;
  if (is_file($module_path . DS . 'fixture.yml')) {
    $fixtures = load_fixture($module, 'fixture.yml');
    $console_db_file_content = "<?php";
    global $mysqli;
    foreach ($fixtures as $table => $records) {
      echo " - import records for table $table\n";
      foreach ($records as $record) {
        $query = "INSERT INTO `$table` ";
        $columns = array();
        $values = array();
        foreach ($record as $column => $val) {
          $columns[] = "`$column`";
          $values[] = is_int($val) ? $val : '"' . $val . '"';
        }
        $query .= "(" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        if ($mysqli->query($query) == FALSE) {
          echo "   * Error: '$query'\n";
        }
      }
    }
  }
}

/**
 * check if a module is enabled
 * 
 * @param type $module
 */
function module_enabled($module) {
  global $enabled_modules;
  return in_array($module, $enabled_modules);
}

/**
 * check if a module exist or not
 * 
 * @param type $module
 * @return type
 */
function module_exists($module) {
  return is_dir(WEBROOT . DS . 'modules' . DS . $module);
}


function js_library_exist($name) {
  $result = shell_exec('find ' . WEBROOT . DS . 'libraries' . DS . $name . '*');
  return !is_null($result);
}

/**
 * If we are in command line or not
 */
function is_cli() {
  return PHP_SAPI == 'cli';
}

/**
 * If we are not at backend of not
 * 
 * @return type
 */
function is_backend() {
  return (preg_match('/^\/admin/', get_request_uri_relative()) && !is_cli());
}

function is_frontend() {
  return !is_backend();
}

/**
 * check if maintenance mode is on
 */
function is_maintenance() {
  $var = Vars::findByName('maintenance');
  if ($var && $var->getValue() == 1) {
    return true;
  }
  return false;
}

/**
 * 
 * @param type $module
 */
function build_schemas($module) {
  $module_path = MODULESROOT . DS . $module;
  if (is_file($module_path . DS . 'schema.yml')) {
    $schemas = load_fixture($module, 'schema.yml');
    $console_db_file_content = "<?php";
    foreach ($schemas as $table => $schema) {
      $fields = $schema['fields'];
      $pk = isset($schema['pk']) ? $schema['pk'] : null;
      $indexes = isset($schema['indexes']) ? $schema['indexes'] : null;
      $fks = isset($schema['fks']) ? $schema['fks'] : null;
      $class = format_as_class_name($table);

      // build template
      ob_start();
      require MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'classes' . DS . 'ClassTemplate.tpl.php'; 
      $content = ob_get_clean();
      $content = "<?php\n" . $content;

      // store template
      if (!is_dir($module_path . DS . 'includes')) {
        mkdir ($module_path . DS . 'includes');
      }
      if (!is_dir($module_path . DS . 'includes' . DS . 'classes')) {
        mkdir ($module_path . DS . 'includes' . DS . 'classes');
      }
      file_put_contents($module_path . DS . 'includes' . DS . 'classes' . DS . 'Base' . $class . '.class.php', $content);
      echo " - Create base class Base$class\n";
      $class_file = $module_path . DS . 'includes' . DS . 'classes' . DS . $class . '.class.php';
      if (!is_file($class_file)) {
        touch($class_file);
        file_put_contents($class_file, '<?php
require_once "Base' . $class . '.class.php";

class ' . $class . ' extends Base' . $class . ' {
  
}
');
        echo " - Create class $class\n";
      } else {
        echo " - class $class already exist. Keep it as is\n";
      }
      
      // prepare $console_db_file_content
      $console_db_file_content .= '
  //-- ' . $class . ':Clear cache
  if ($command == "cc") {
    if ($arg1 == "all" || $arg1 == "' . $module . '") {
      echo " - Drop table \'' . $table . '\' ";
      echo ' . $class . '::dropTable() ? "success\n" : "fail\n";
    }
  }

  //-- ' . $class . ':Import DB
  if ($command == "import" && $arg1 == "db" && (is_null($arg2) || $arg2 == "' . $table . '") ) {
  //- create tables if not exits
  echo " - Create table \'' . $table . '\' ";
  echo ' . $class . '::createTableIfNotExist() ? "success\n" : "fail\n";
  }
  ';
    }
    
    // inject console scripts
    // create console file if not exist
    $console_file = $module_path . DS . 'console.php';
    if (!is_file($console_file)) {
      touch($console_file);
    }
    // inject include script
    $content = file_get_contents($console_file);
    if (!preg_match('/^<\?php\nrequire_once "console_db.php";/', $content)) {
      file_put_contents($console_file, '<?php' . "\n" . 'require_once "console_db.php";' . "\n" . preg_replace('/^<\?php\s?/', '', $content));
    }
    // create console_db file if not exist
    $console_db_file = $module_path . DS . 'console_db.php';
    if (!is_file($console_db_file)) {
      touch ($console_db_file);
    }
    file_put_contents($console_db_file, $console_db_file_content);
    
  }
}

function build_form($model, $override = false) {
  global $enabled_modules;
  // check each module's schema.yml file, see if we can find a form for the model to build
  foreach ($enabled_modules as $module) {
    if (is_file(MODULESROOT . DS . $module . DS . 'schema.yml')) {
      $schemas = load_fixture($module, 'schema.yml');
      // when found, build the form
      if (array_key_exists($model, $schemas)) {
        $schema = $schemas[$model];
        if (isset($schema['form'])) {
          // load form fields
          $form_fields = array();
          foreach ($schema['form']['fields'] as $field => $conf) {
            $f = array();
            $f['widget'] = $conf['widget'];
            $f['widget_class'] = 'FormWidget' . format_as_class_name($conf['widget']);
            $f['widget_conf'] = isset($conf['widget_conf']) ? $conf['widget_conf'] : array();
            
            $form_fields[$field] = $f;
          }
          
          $module_path = MODULESROOT . DS . $module;
          // create controllers folder if not exist
          if (!is_dir($module_path . DS . 'controllers')) {
            mkdir($module_path . DS . 'controllers');
          }
          if (!is_dir($module_path . DS . 'controllers' . DS . 'backend')) {
            mkdir($module_path . DS . 'controllers' . DS . 'backend');
          }
          // create templates folder if not exist
          if (!is_dir($module_path . DS . 'templates')) {
            mkdir($module_path . DS . 'templates');
          }
          if (!is_dir($module_path . DS . 'templates' . DS . 'backend')) {
            mkdir($module_path . DS . 'templates' . DS . 'backend');
          }
          // create routing.yml file if not exist
          if (!is_file($module_path . DS . 'routing.yml')) {
            touch ($module_path . DS . 'routing.yml');
            file_put_contents($module_path . DS . 'routing.yml', "routing:");
          }
          // create bootstrap.php file if not exist
          if (!is_file($module_path . DS . 'bootstrap.php')) {
            touch ($module_path . DS . 'bootstrap.php');
            file_put_contents($module_path . DS . 'bootstrap.php', "<?php\n");
          }
          // build bootstrap.php
          $bootstrap = file_get_contents($module_path . DS . 'bootstrap.php');
          if (strpos($bootstrap, "admin/$model/list") === false) {
            echo " + module $module: add code snippet to bootstrap.php for model '$model'\n";
            
            ob_start();
            require MODULESROOT . DS . 'core' . DS . 'bootstrap.template.php';
            $content = ob_get_clean();
            file_put_contents($module_path . DS . 'bootstrap.php', $content, FILE_APPEND);
          } else {
            echo " > module $module: code snippet exists for model '$model'. skip\n";
          }
          // build routings.yml
          $routing = file_get_contents($module_path . DS . 'routing.yml');
          if (!preg_match('/admin_'.$model.'_list/', $routing)) {
            ob_start();
            require MODULESROOT . DS . 'core' . DS . 'routing.yml.php';
            $content = ob_get_clean();
            file_put_contents($module_path . DS . 'routing.yml', $content, FILE_APPEND);
          }
          
          
          $module;
          $model;
          $fields = array_keys($schema['fields']);
          $form_fields;
          $model_class = format_as_class_name($model);
          $model_names = $schema['form']['names'];
          
          
          // build controllers
          foreach (array('list', 'delete', 'create', 'edit') as $controller) {
            if ($override || !is_file($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_$controller.php")) {
              echo " + module $module: create controller '".$model."_$controller.php'\n";

              ob_start();
              require (MODULESROOT . DS . 'core' . DS . 'controllers' . DS . 'backend' . DS . "$controller.template.php");
              $content = "<?php\n" . ob_get_clean();
              file_put_contents($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_$controller.php", $content);
            } else {
              echo " > module $module: controller '".$model."_$controller.php' exists. skip\n";
            }
          }
          
          // build templates
          foreach (array('list', 'create', 'edit') as $template) {
            Asset::clearDynamicAssets();
            
            if ($override || !is_file($module_path . DS . 'templates' . DS . 'backend' . DS . $model."_$template.tpl.php")) {
              echo " + module $module: create template '".$model."_$template.tpl.php'\n";

              ob_start();
              require(MODULESROOT . DS . 'core' . DS . 'templates' . DS . 'backend' . DS . "$template.tpl.template.php");
              $content = ob_get_clean();
              $content = str_replace('[[[', '<?php', $content);
              $content = str_replace(']]]', '?>', $content);
              file_put_contents($module_path . DS . 'templates' . DS . 'backend' . DS . $model."_$template.tpl.php", $content);
            } else {
              echo " > module $module: template '".$model."_$template.tpl.php' exists. skip\n";
            }
          }
          
          // build field controllers
          foreach ($form_fields as $name => $field) {
            $widget_conf = $field['widget_conf'];
            // image field controllers
            if ($field['widget'] == 'image') {
              // image submission controller
              $upload_dir = $widget_conf['upload_dir'];
              $transform = isset($widget_conf['transform']) ? $widget_conf['transform'] : false;
              if ($transform) {
                $dimension = isset($transform['dimension']) ? explode("x", $transform['dimension']) : 0;
                $refill = isset($transform['refill']) ? $transform['refill'] : 0;
                $watermark = isset($transform['watermark']) ? $transform['watermark'] : 0;
              }

              if ($override || !is_file($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_form_field_$name.php")) {
                echo " + module $module: create form field controller '".$model."_form_field_$name.php'\n";

                ob_start();
                require (MODULESROOT . DS . 'core' . DS . 'controllers' . DS . 'backend' . DS . "form_field_image.template.php");
                $content = "<?php\n" . ob_get_clean();
                file_put_contents($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_form_field_$name.php", $content);
              } else {
                echo " > module $module: controller '".$model."_form_field_$name.php' exists. skip\n";
              }
              
              // image remove controller
              if ($override || !is_file($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_form_field_$name"."_remove.php")) {
                echo " + module $module: create form field remove controller '".$model."_form_field_$name"."_remove.php'\n";

                ob_start();
                require (MODULESROOT . DS . 'core' . DS . 'controllers' . DS . 'backend' . DS . "form_field_image_remove.template.php");
                $content = "<?php\n" . ob_get_clean();
                file_put_contents($module_path . DS . 'controllers' . DS . 'backend' . DS . $model."_form_field_$name"."_remove.php", $content);
              } else {
                echo " > module $module: controller '".$model."_form_field_$name"."_remove.php' exists. skip\n";
              }
            }
          }
        }
        return;
      }
    }
  }
}

/**
 * Format first_second to FirstSecond
 * 
 * @param type $name
 * @return type
 */
function format_as_class_name($name) {
  $tokens = explode('_', $name);
  $rtn = array();
  foreach ($tokens as $token) {
    $rtn[] = ucfirst(strtolower($token));
  }
  return implode('', $rtn);
}

/**
 * Escape double quote
 * @param type $var
 * @return type
 */
function escapeDoubleQuote($var) {
  return str_replace('"', '&quot;', $var);
}

/**
 * Escape single quote
 * 
 * @param type $var
 * @return type
 */
function escapeSingleQuote($var) {
  return str_replace("'", "&#39;", $var);
}

/**
 * check if the user if from mobile or not
 * 
 * @return type
 */
function is_mobile() {
  // if no session set, we try to tell from http header
  if (!isset($_SESSION['mobile'])) {
    load_library_mobile_detect();
    $detect = new Mobile_Detect;
    $_SESSION['mobile'] = $detect->isMobile();
    return $_SESSION['mobile'];
  // otherwise, return session value
  } else {
    return $_SESSION['mobile'] == true ? true : false;
  }
}

/**
 * get user's agent
 * 
 * @return string
 */
function get_agent() {
  if (is_mobile()) {
    return 'mobile';
  } else {
    return 'desktop';
  }
}

function load_library_mobile_detect() {
  require_once  MODULESROOT . DS . 'core' . DS . 'includes' . DS . 'libraries' . DS . 'mobile-detect' . DS . 'Mobile_Detect.php';
}

function encrypt($content) {
  // add random char at both ends
  $content = get_random_string(4) . $content . get_random_string(4);
  // base64_encode
  $content = base64_encode($content);
  // swap at position 4
  $content = substr($content, 4, strlen($content) - 4) . substr($content, 0, 4);
  // reverse it
  $content = strrev($content);
  return $content;
}

function decrypt($content) {
  // reverse it
  $content = strrev($content);
  // swap at position -4
  $content = substr($content, -4) . substr($content, 0, strlen($content) - 4);
  // base64_decode
  $content = base64_decode($content);
  // strip random chars
  $content = substr($content, 4, strlen($content) - 8);
  return $content;
}

function mkdir_recursively($dir) {
  $tokens = explode('/', $dir);
  $path = "";
  foreach ($tokens as $token) {
    if ($token == "") {
      continue;
    }
    $path .= "/$token";
    $path = rtrim($path, "/");
    if (!is_dir($path)) {
      mkdir($path);
    } else {
      continue;
    }
  }
}

/**
 * go to last accessed page, excluding current page
 */
function gobackurl($url_to_skip = false, $no_history_goto = false) {
  // prepare url_to_skip
  if ($url_to_skip == false) {
    $url_to_skip = get_cur_page_url();
  } else {
    $skip_postfix = $url_to_skip;
  }
  // prepare no_history_goto
  if ($no_history_goto == false) {
    $no_history_goto = uri('');
  }
  
  $rtn = false;
  
  // only update gobackurl when current url does not match stored history url
  if ($url_to_skip == false) {
    if ($_SESSION['gobackurl'] != get_cur_page_url()) {
      $rtn = $_SESSION['gobackurl'];
      $_SESSION['gobackurl'] = get_cur_page_url();
    }
  } else {
    if (!preg_match('/'.  preg_quote($url_to_skip, '/')  .'/', get_cur_page_url())) {
      $rtn = $_SESSION['gobackurl'];
      $_SESSION['gobackurl'] = get_cur_page_url();
    }
  }
  
  return $rtn ? $rtn : $no_history_goto;
}