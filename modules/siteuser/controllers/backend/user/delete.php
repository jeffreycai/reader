<?php

$uid = $vars[1];
$class = class_exists('MySiteUser') ? 'MySiteUser' : 'SiteUser';
$user = $class::findById($uid, $class);
if (empty($user)) {
  HTML::forward('core/404');
}

if ($user->delete()) {
  Message::register(new Message(Message::SUCCESS, i18n(array('en' => 'User <i>' . $user->getUsername() . '</i> deleted successfully.', 'zh' => '用户 <i>' . $user->getUsername() . '</i> 删除成功'))));
} else {
  Message::register(new Message(Message::DANGER, i18n(array('en' => 'User <i>' . $user->getUsername() . '</i> deleted failed.', 'zh' => '用户 <i>' . $user->getUsername() . '</i> 删除失败'))));
}
HTML::forwardBackToReferer();