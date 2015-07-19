<?php
$uid = isset($vars[1]) ? $vars[1] : null;
$salt = isset($vars[2]) ? $vars[2] : null;
$salt = is_null($salt) ? $salt : decrypt($salt);

// validation
if (is_null($uid) || is_null($salt)) {
  HTML::forward('core/404');
}
$user = SiteUser::findById($uid);
if (is_null($user) || $user->getSalt() != $salt) {
  HTML::forward('core/404');
}

// do resend email
$user->sendAccountActivationEmail();
Message::register(new Message(Message::SUCCESS, i18n(array(
    'en' => 'Account activation email resent successfully. Please check your mail box',
    'zh' => '账号激活邮件发送成功，请查看您的邮箱'
))));
HTML::forwardBackToReferer();