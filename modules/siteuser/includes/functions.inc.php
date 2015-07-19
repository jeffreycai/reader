<?php
function require_permission($permissions) {
  if (has_permission($permissions)) {
    return true;
  } else {
    http_response_code(401);
    dispatch('siteuser/user_login');
    exit;
  }
}

function require_role($roles) {
  if (has_role($roles)) {
    return true;
  } else {
    http_response_code(401);
    dispatch('siteuser/user_login');
    exit;
  }
}

function require_login() {
  if (!is_login()) {
    HTML::forward('users');
  }
}

function has_permission($permissions) {
  $user = SiteUser::getCurrentUser();
  return $user->hasPermission($permissions);
}

function has_role($roles) {
  $user = SiteUser::getCurrentUser();
  return $user->hasRole($roles);
}

function is_login() {
  $user = SiteUser::getCurrentUser();
  return $user->getId() != -1;
}


function unauthorised_action() {
  HTML::forward('users/unauthorised');
}