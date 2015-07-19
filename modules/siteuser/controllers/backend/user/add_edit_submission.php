<?php
/**
 * Please set $uid before including this file, if updating existing user
 */
$uid = isset($uid) ? $uid : null;
$user = isset($user) ? $user : null;

if (isset($_POST['submit'])) {
  $username = isset($_POST['username']) ? strip_tags(trim($_POST['username'])) : null;
  $email    = isset($_POST['email'])    ? strip_tags(trim($_POST['email']))    : null;
  $password = isset($_POST['password']) ? strip_tags(trim($_POST['password'])) : null;
  $password_confirm = isset($_POST['password_confirm']) ? strip_tags(trim($_POST['password_confirm'])) : null;
  $roles    = isset($_POST['roles']) && is_array($_POST['roles']) ? $_POST['roles'] : array();
  $noemailnotification = isset($_POST['noemailnotification']) ? true : false;
  if (is_backend()) {
    $active   = isset($_POST['active'])   ? strip_tags(trim($_POST['active']))   : null;
  }
  
  // validation
  $messages = array();
  
  // spam token for frontend only
  if (is_frontend()) {
    if (module_enabled('form') && !Form::checkSpamToken(SITEUSER_FORM_SPAM_TOKEN)) {
      $messages[] = new Message(Message::DANGER, i18n(array(
          'en' => 'Form expired. Please try submit again',
          'zh' => '表单超时，请重新尝试提交表单'
      )));
    }
  }
  
  // username
  if (is_null($username)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter your username',
        'zh' => '请填写用户名'
    )));
  } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Username needs to be composed by alphabetically letters or underscore',
        'zh' => '用户名必须为英文字母或者下划线'
    )));
  } else if ($user = SiteUser::findByUsername($username)) {
    // when create new user, we check if there is an existing one
    if (empty($uid)) {
      $messages[] = new Message(Message::DANGER, i18n(array(
          'en' => 'This username has already been registered. Please choose a different username',
          'zh' => '该用户名已被注册，请尝试其他用户名'
      )));
    } else {
    // when update existing user, we check if the username is duplicated
      if ($user->getId() != $uid) {
        $messages[] = new Message(Message::DANGER, i18n(array(
            'en' => 'This username has already been registered. Please choose a different username',
            'zh' => '该用户名已被注册，请尝试其他用户名'
        )));
      }
    }
  }
  // email
  if (is_null($email)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter your email',
        'zh' => '请填写电子邮箱'
    )));
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter a valid email address',
        'zh' => '请填写合法的邮箱地址'
    )));
  } else if ($user = SiteUser::findByEmail($email)) {
    // when create new user, we check if there is an existing one
    if (empty($uid)) {
      $messages[] = new Message(Message::DANGER, i18n(array(
          'en' => 'This email has already been registered. Please choose a different email',
          'zh' => '该邮箱已被注册，请尝试其他邮箱'
      )));
    } else {
    // when update existing user, we check if the username is duplicated
      if ($user->getId() != $uid) {
        $messages[] = new Message(Message::DANGER, i18n(array(
            'en' => 'This email has already been registered. Please choose a different email',
            'zh' => '该邮箱已被注册，请尝试其他邮箱'
        )));
      }
    }
  }
  // password
  if (is_null($password)) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter your password',
        'zh' => '请填写密码'
    )));
  } else if (strlen($password) < 6) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Please enter a password more than 6 characters',
        'zh' => '密码长度最少应为6个字节，请选择一个更长的密码'
    )));
  } else if ($password != $password_confirm) {
    $messages[] = new Message(Message::DANGER, i18n(array(
        'en' => 'Your password and confirmed password don\'t match. Please try again',
        'zh' => '确认密码和原密码不匹配，请重新输入'
    )));
  }
  
  // profile
  if (module_enabled('siteuser_profile')) {
    require MODULESROOT . '/siteuser_profile/controllers/fields_validation.php';
  }
  
  // eorror handling
  if (sizeof($messages) > 0) {
    foreach ($messages as $message) {
      Message::register($message);
    }
    
  // if success
  } else {
    $user = empty($uid) ? new SiteUser() : SiteUser::findById($uid);
    
    $user->setUsername($username);
    $user->setEmail($email);
    $user->putPassword($password);
    if (is_backend()) {
      $user->setActive(empty($active) ? 0 : 1);
      $user->setEmailActivated(1);
    }
    
    // for new user
    if (empty($uid)) {
      $user->setCreatedAt(time());
      // if $noemailnotification flag is not set
      if (!$noemailnotification) {
        $user->setEmailActivated(0);
      }
    }
    
    if ($user->save()) {
      // update profile
      if (module_enabled('siteuser_profile')) {
        require MODULESROOT . '/siteuser_profile/controllers/fields_update.php';
      }
      
      if (empty($uid)) {
        if (!$noemailnotification) {
          $user->sendAccountActivationEmail();
          Message::register(new Message(Message::SUCCESS, i18n(array(
              'en' => 'Thank you for registering with us. An activation email has been sent to your mail box. Please activate your account by clicking the link in the mail.',
              'zh' => '感谢您注册新帐号。我们刚给您的注册邮箱发送了一份帐号激活邮件，请点击邮件内的激活链接'
          )). '<br /><br />'.i18n(array(
              'en' => 'After you activate your account, you can ',
              'zh' => '激活您的账号后，您可以'
          )).'<a href="'.uri('users').'">'.i18n(array(
              'en' => 'login here',
              'zh' => '在此登录'
          )).'</a>'));
        } else {
          Message::register(new Message(Message::SUCCESS, i18n(array(
              'en' => 'New user created successfully',
              'zh' => '新用户添加成功'
          ))));
          // clear $_POST so that our form is not pre-populated
          unset($_POST);
        }
      } else {
        Message::register(new Message(Message::SUCCESS, i18n(array(
            'en' => 'User updated successfully',
            'zh' => '用户更新成功'
        ))));
      }
      // update user-role
      if (is_backend()) {
        if (!empty($roles)) {
          // delete existing relationships for user - role if update
          if (!empty($uid)) {
            $urs = SiteUserRole::findByUid($uid);
            foreach ($urs as $ur) {
              $ur->delete();
            }
          }

          // create new relationships for user - role
          foreach ($roles as $rid => $val) {
            if ($val) {
              $ur = new SiteUserRole();
              $ur->setRoleId($rid);
              $ur->setUserId($user->getId());
              $ur->save();
            }
          }
        }
      }
    } else {
      Message::register(new Message(Message::DANGER, i18n(array(
          'en' => 'Sorry, there is a system error when processing your request',
          'zh' => '抱歉，系统出错了'
      ))));
    }
  }
}
