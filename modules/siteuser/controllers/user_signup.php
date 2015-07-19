<?php

// check if already login, if yes, redirect to homepage
if (is_login()) {
  HTML::forward('');
}

// handle submission
$submission_handler = MODULESROOT . '/siteuser/controllers/backend/user/add_edit_submission.php';
require $submission_handler;


// override this call if "site" module has the override controller
$override_controller = MODULESROOT . '/site/controllers/siteuser/user_signup.php';
if (is_file($override_controller)) {
  require $override_controller;
  exit;
}



$html = new HTML();



$html->renderOut('core/backend/single_form_header', array('title' => i18n(array(
          'en' => 'New user signup',
          'zh' => '新用户注册'
      ))));
echo SiteUser::renderSignupForm(null, '', array('avatar', 'active'));
$html->renderOut('core/backend/single_form_footer', array(
    'extra' => '<div  style="text-align: center;"><small class="login"><a href="'.uri('users').'">'.i18n(array('en' => 'login as exsiting user', 'zh' => '现有用户登录')).'</a></small></div>'
));


exit;