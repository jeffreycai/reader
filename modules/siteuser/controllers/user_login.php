<?php

// check if already login, if yes, redirect to homepage
if (is_login()) {
  HTML::forward('');
}

// override this call if "site" module has the override controller
$override_controller = MODULESROOT . '/site/controllers/siteuser/user_login.php';
if (is_file($override_controller)) {
  require $override_controller;
  exit;
}



$html = new HTML();



$html->renderOut('core/backend/single_form_header', array('title' => i18n(array(
          'en' => 'User login',
          'zh' => '用户登录'
      ))));
echo SiteUser::renderLoginForm();
$html->renderOut('core/backend/single_form_footer', array(
    'extra' => '<div  style="text-align: center;"><small class="signup"><a href="'.uri('users/signup').'">'.i18n(array('en' => 'signup as new user', 'zh' => '申请注册为新用户')).'</a></small></div>'
));


exit;