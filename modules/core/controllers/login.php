<?php
$user = User::getInstance();

// we only do stuff when the user is not login
if (!$user->isLogin()) {
  $isSubmit = isset($_POST['submit']) ? true : false; // is submission or not;
  // deal with form submission
  if ($isSubmit) {
    // check spam
    if (module_enabled('form') && !Form::checkSpamToken(UID_BACKEND_LOGIN_FORM)) {
      $message = new Message(Message::DANGER, 'Form submission error.');
      Message::register($message);
      HTML::forwardBackToReferer();
    }
    
    
    // authentication
    $authentication_success = false;
    $email = isset($_POST['email']) ? strip_tags($_POST['email']) : null;
    $password = isset($_POST['password']) ? strip_tags($_POST['password']) : null;
    
    $settings = Vars::getSettings();
    foreach($settings['users'] as $u) {
      if ($u['email'] == $email && $u['password'] == $password) {
        $authentication_success = true;
      }
    }
    // if success
    if ($authentication_success) {
      $user = User::findByEmail($email);
      $user->login();
      
      HTML::forwardBackToReferer();
    // if fail
    } else {
      $message = new Message(Message::DANGER, 'Username or password incorrect. Please try again.');
      Message::register($message);
    }
    
  // if not form submission, show the login form
  }
  
  
      /** views **/
      $html = new HTML();

      $html->renderOut('core/backend/html_header', array(
        'title' => 'Please login',
      ), true);

      $html->renderOut('core/backend/single_form_header', array('title' => i18n(array(
          'en' => 'Backend login',
          'zh' => '登录后台'
      ))));
      $html->renderOut('core/backend/login', array(), true);
      $html->renderOut('core/backend/single_form_footer');

      $html->renderOut('core/backend/html_footer');
      
      exit;
      
// if already login, go to admin home apge
} else {
  HTML::forward('admin');
}


