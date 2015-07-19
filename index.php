<?php
require_once('bootstrap.php');

$settings = Vars::getSettings();

/** rounting **/
$relative_uri;
if ($settings['i18n']) {
  $relative_uri = get_request_uri_relative();
} else {
  $relative_uri = str_replace(get_sub_root(), '', get_request_uri());
}
//_debug($settings['routing']);
// try to match a route
foreach ($settings['routing'] as $route) {
  $path = $route['path'];
  $isSecure = $route['isSecure'];
  $controller = $route['controller'];
  $i18n = $route['i18n'];

//  _debug($relative_uri);
  $vars = array();
  $user = User::getInstance();
  if (preg_match('/'.$path.'/', $relative_uri, $vars)) {
    // redirect to lang url if lang code is not here
    if ($i18n && $settings['i18n']) {
      HTML::redirectToI18nUrl();
    }
    
    if ($isSecure && !$user->isLogin()) {
      dispatch('core/login');
    } else {
      dispatch($controller, $vars);
    }
    exit;
  }
}


// if no route matches, check if matches any page
if ($settings['i18n']) {
  HTML::redirectToI18nUrl();
}

global $enabled_modules;
if (in_array('database', $enabled_modules) && in_array('page', $enabled_modules) && Page::tableExist()) {
  foreach (Page::findAllPublished() as $page) {
    if ($page->getUri() == $relative_uri) {
      dispatch('site/' . $page->getController(), array('page' => $page));
      exit;
    }
  }
}

dispatch('site/404');

exit;

