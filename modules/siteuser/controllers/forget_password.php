<?php

// check if already login, if yes, redirect to homepage
if (is_login()) {
  HTML::forward('');
}

// handle form submission
if (isset($_POST['submit'])) {
  $messages = array();
  $email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : null;
  
  /**  validation **/
  if (empty($email)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter the e-mail address you registered with us',
        'zh' => '请填写您注册是使用的电子邮箱'
    )));
  } else {
    $user = SiteUser::findByEmail($email);
    if (is_null($user)) {
      $messages[] = new Message(Message::DANGER, i18n(array(
          'en' => 'No record found registered with this e-mail',
          'zh' => '未找到使用该邮箱注册的记录'
      )));
    }
  }
  // if succeed, send email
  if (sizeof($messages) == 0) {
    if ($user = SiteUser::findByEmail($email)) {
      $user->sendPasswordResetEmail();
    }
    
    Message::register(new Message(Message::SUCCESS, i18n(array(
        'en' => 'We\'ve sent an email to your mail box to reset your password. Please check your mail box.',
        'zh' => '我们已向您注册的邮箱发送密码重置链接，请查看您的邮箱并点击链接重置您的密码'
    ))));
  }  else {
    Message::register($messages);
  }
  HTML::forwardBackToReferer();
}

// override this call if "site" module has the override controller
$override_controller = MODULESROOT . '/site/controllers/siteuser/forget_password.php';
if (is_file($override_controller)) {
  require $override_controller;
  exit;
}



$html = new HTML();


$html->renderOut('core/backend/html_header', array('title' => i18n(array(
    'en' => 'Password reset',
    'zh' => '重置密码'
))));

$html->renderOut('core/backend/single_form_header', array('title' => i18n(array(
      'en' => 'Password reset',
      'zh' => '重置密码'
  ))));
echo SiteUser::renderForgetPasswordForm();
$html->renderOut('core/backend/single_form_footer', array(
    'extra' => '<div class="login" style="text-align: center;"><small><a href="'.uri('users').'">'.i18n(array('en' => 'go back to login page', 'zh' => '返回登录页面')).'</a></small></div>'
));

$html->renderOut('core/backend/html_footer');

exit;