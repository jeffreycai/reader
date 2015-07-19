<?php

$username = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : null;
$password = isset($_POST['password']) ? trim(strip_tags($_POST['password'])) : null;
$remember = isset($_POST['remember']) ? trim(strip_tags($_POST['remember'])) : null;

$user = null;

/**  validation **/
$messages = array();
if (empty($username)) {
  $messages[] = new Message(Message::DANGER, i18n(array(
      'en' => 'Please enter your e-mail or username',
      'zh' => '请填写电子邮箱或用户名'
  )));
}
if (empty($password)) {
  $messages[] = new Message(Message::DANGER, i18n(array(
      'en' => 'Please enter your password',
      'zh' => '请填写密码'
  )));
}

if (!empty($messages)) {
  Message::register($messages);

  HTML::forwardBackToReferer();
}

// check if user exists
$class = class_exists('MySiteUser') ? 'MySiteUser' : 'SiteUser';
if (strpos($username, '@') == false) {
  $user = $class::findByUsername($username, $class);
} else {
  $user = $class::findByEmail($username, $class);
}
if (is_null($user) || !$user->checkPassword($password) || $user->getActive() == 0) {
  Message::register(new Message(Message::DANGER, i18n(array(
      'en' => 'Username and password don\'t match. Please try again',
      'zh' => '用户名和密码不匹配，请重新尝试'
  ))));

  HTML::forwardBackToReferer();
} else if ($user->getEmailActivated() == 0) {
  Message::register(new Message(Message::DANGER, i18n(array(
      'en' => 'Your account is not yet activated. To resend the activation email, please <a href="'.uri('user/'.$user->getId().'/activate_resend_email/'.encrypt($user->getSalt()), false).'">click here</a>',
      'zh' => '您的账号还未激活。如需重新发送激活邮件，请<a href="'.uri('user/'.$user->getId().'/activate_resend_email/'.encrypt($user->getSalt()), false).'">点击此处</a>'
  ))));

  HTML::forwardBackToReferer();
}

// check spam
if (module_enabled('form') && !Form::checkSpamToken(SITEUSER_FORM_SPAM_TOKEN)) {
  $message = new Message(Message::DANGER, i18n(array(
      'en' => 'Form login session expired. Please try again',
      'zh' => '表单提交时限过期，请重新尝试登录'
  )));
  Message::register($message);
  HTML::forwardBackToReferer();
}

/** login action **/
$user->login(is_null($remember) ? false : true);
// forward back to referer if exists
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
if ($referer
        && !preg_match('/\/users\/?$/', $referer) // we don't go back to login for loop
        && !preg_match('/\/confirm\/?$/', $referer) // we don't go back to 'confirm' page as it doesn't have meaningful message
        && !preg_match('/forget\-password\/?$/', $referer)
) {
  HTML::forward($referer);
}
HTML::forward('');