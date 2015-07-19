<?php
$uid = isset($vars[1]) ? trim(strip_tags($vars[1])) : null;
$salt = isset($vars[2]) ? trim(strip_tags($vars[2])) : null;
$salt = is_null($salt) ? $salt : decrypt($salt);

// validation
if (is_null($uid) || is_null($salt)) {
  HTML::forward('core/404');
}
$user = SiteUser::findById($uid);

if (is_null($user) || $salt != $user->getSalt()) {
  Message::register(new Message(Message::INFO, i18n(array(
      'en' => 'This password reset link has expired',
      'zh' => '本密码重置链接已失效'
  )).'<br /><small><a href="'.uri('users').'">'.i18n(array(
      'en' => 'go to login page',
      'zh' => '前往登录页面'
  )).'</a></small>'));
  HTML::forward('confirm');
}

// process submission
if (isset($_POST['submit'])) {
  $password = isset($_POST['password']) ? trim(strip_tags($_POST['password'])) : null;
  $password_confirm = isset($_POST['password_confirm']) ? trim(strip_tags($_POST['password_confirm'])) : null;
  // validation
  if (is_null($password) || strlen($password) < 6) {
    Message::register(new Message(Message::DANGER, i18n(array(
        'en' => 'Password needs to be more than 6 characters. Please try again',
        'zh' => '密码至少需要6位。请重试'
    ))));
    HTML::forwardBackToReferer();
  } else if ($password != $password_confirm) {
    Message::register(new Message(Message::DANGER, i18n(array(
        'en' => 'Password and confirmed password don\'t match. Please try again',
        'zh' => '密码和确认密码不符。 请重试'
    ))));
    HTML::forwardBackToReferer();
  }
  // success
  $user->putPassword($password);
  $user->save();
  Message::register(new Message(Message::SUCCESS, i18n(array(
      'en' => 'Your password has been successfully updated. You may sign in below',
      'zh' => '您的密码已经成功更新了。您现在可以登录了'
  ))));
  HTML::forward('users');
}


// override this call if "site" module has the override controller
$override_controller = MODULESROOT . '/site/controllers/siteuser/forget_password_reset.php';
if (is_file($override_controller)) {
  require $override_controller;
  exit;
}


// default
$html = new HTML();

$html->renderOut('core/backend/single_form_header', array('title' => i18n(array(
          'en' => 'Reset your password',
          'zh' => '重置您的密码'
      ))));
echo SiteUser::renderPasswordResetForm();
$html->renderOut('core/backend/single_form_footer', array(
    'extra' => '<div  style="text-align: center;"><small class="signup"><a href="'.uri('users').'">'.i18n(array('en' => 'go back to login', 'zh' => '返回登录界面')).'</a></small></div>'
));


exit;