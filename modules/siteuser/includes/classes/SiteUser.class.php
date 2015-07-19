<?php
require_once "BaseSiteUser.class.php";

class SiteUser extends BaseSiteUser {
  static function findAllWithPage($page, $entries_per_page, $order_by = null, $order = 'DESC', $instance='SiteUser') {
    global $mysqli;
    $query = "SELECT * FROM site_user LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page . ($order_by ? " ORDER BY $order_by $order" : "");
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  static function renderUpdateFormBackend(SiteUser $user = null, $action = '') {
    // set default action value
    if ($action != '') {
      $action = uri($action);
    }
    
    // get vars from form submission
    $username = isset($_POST['username'])  ? strip_tags($_POST['username'])  : (isset($user) ? $user->getUsername()  : '');
    $email    = isset($_POST['email'])     ? strip_tags($_POST['email'])     : (isset($user) ? $user->getEmail()     : '');
    $password = '';
    $password_confirm = '';
    $active   = isset($_POST['active'])    ? strip_tags($_POST['active'])    : (isset($user) ? $user->getActive()    : false);
    
    $mandatory_label = ' <span style="color: rgb(185,2,0); font-weight: bold;">*</span>';
    
    $roles_form_markup = '<div id="form-field-roles"><label>Roles</label><ul class="checkbox">';
    foreach (SiteRole::findAll() as $role) {
      $roles_form_markup .= '<li><label><input type="checkbox" name="roles['.$role->getid().']" value=1 '.(isset($_POST['roles']) ? (isset($_POST['roles'][$role->getId()]) ? 'checked="checked"' : '') : ($user &&$user->hasRole($role->getName()) ? 'checked="checked"' : '')).' />' . $role->getName() . '</label></li>';
    }
    $roles_form_markup .= '</ul></div>';
    
    $rtn = '
<form action="'.$action.'" method="POST" id="adduser" enctype="multipart/form-data">
  <div class="form-group" id="form-field-username">
    <label for="username">'.i18n(array('en' => 'Username', 'zh' => '用户名')).$mandatory_label.' <small style="font-weight: normal;"><i>('.  i18n(array('en' => 'alphabetical letters, number or underscore', 'zh' => '英文字母，数字或下划线')).')</i></small></label>
    <input type="text" class="form-control" id="username" name="username" value="'.$username.'" required placeholder="" />
  </div>
  <div class="form-group" id="form-field-email" >
    <label for="email">'.i18n(array('en' => 'Email', 'zh' => '电子邮箱')).$mandatory_label.'</label>
    <input type="email" class="form-control" id="email" name="email" value="'.$email.'" required />
  </div>
  <div class="form-group" id="form-field-password">
    <label for="password">'.i18n(array('en' => 'Password', 'zh' => '密码')).$mandatory_label.' <small style="font-weight: normal;"><i>('.i18n(array('en' => 'at least 6 letters', 'zh' => '至少6位')).')</i></small></label>
    <input type="password" class="form-control" id="password" name="password" value="'.$password.'" required />
  </div>
  <div class="form-group" id="form-field-password_confirm">
    <label for="password_confirm">'.i18n(array('en' => 'Password again', 'zh' => '再次确认密码')).$mandatory_label.'</label>
    <input type="password" class="form-control" id="password_confirm" name="password_confirm" value="'.$password_confirm.'" required />
  </div>
  ' . (class_exists('SiteProfile') ? SiteProfile::renderUpdateForm($user) : '') . '
  <div class="checkbox" id="form-field-active">
    <label>
      <input type="checkbox" id="active" name="active" value="1" '.($active == false ? '' : 'checked="checked"').'> '.  i18n(array('en' => 'Active?', 'zh' => '有效用户')).'
    </label>
  </div>
  <input type="hidden" value=1 name="noemailnotification" />
  ' . (is_backend() ? $roles_form_markup : '') . '
  <div class="form-group" id="form-field-notice"><small><i>
    '.$mandatory_label.i18n(array(
        'en' => ' indicates mandatory fields',
        'zh' => ' 标记为必填项'
    )).'
  </i></small></div>
  <button type="submit" name="submit" class="btn btn-primary">'.(is_null($user) 
            ? i18n(array('en' => 'Add new user', 'zh' => '添加新用户')) 
            : i18n(array('en' => 'Update user', 'zh' => '更新用户'))).'</button>
</form>
';
    return $rtn;
  }
  
  
    static function renderSignupForm(SiteUser $user = null, $action = '', $exclude_fields = array()) {
    // set default action value
    if ($action != '') {
      $action = uri($action);
    }
    
    // get vars from form submission
    $username = isset($_POST['username'])  ? strip_tags($_POST['username'])  : (isset($user) ? $user->getUsername()  : '');
    $email    = isset($_POST['email'])     ? strip_tags($_POST['email'])     : (isset($user) ? $user->getEmail()     : '');
    $password = '';
    $password_confirm = '';
    $active   = isset($_POST['active'])    ? strip_tags($_POST['active'])    : (isset($user) ? $user->getActive()    : false);
    
    $mandatory_label = ' <span style="color: rgb(185,2,0); font-weight: bold;">*</span>';
    
    $active_field = '
  <div class="checkbox" id="form-field-active">
    <label>
      <input type="checkbox" id="active" name="active" value="1" '.($active == false ? '' : 'checked="checked"').'> '.  i18n(array('en' => 'Active?', 'zh' => '有效用户')).'
    </label>
  </div>
  ';
    
    $rtn = Message::renderMessages() . '
<form action="'.$action.'" method="POST" id="signup" enctype="multipart/form-data">
  <div class="form-group" id="form-field-username">
    <label for="username">'.i18n(array('en' => 'Username', 'zh' => '用户名')).$mandatory_label.' <small style="font-weight: normal;"><i>('.  i18n(array('en' => 'alphabetical letters, number or underscore', 'zh' => '英文字母，数字或下划线')).')</i></small></label>
    <input type="text" class="form-control" id="username" name="username" value="'.$username.'" required placeholder="" />
  </div>
  <div class="form-group" id="form-field-email" >
    <label for="email">'.i18n(array('en' => 'Email', 'zh' => '电子邮箱')).$mandatory_label.'</label>
    <input type="email" class="form-control" id="email" name="email" value="'.$email.'" required />
  </div>
  <div class="form-group" id="form-field-password">
    <label for="password">'.i18n(array('en' => 'Password', 'zh' => '密码')).$mandatory_label.' <small style="font-weight: normal;"><i>('.i18n(array('en' => 'at least 6 letters', 'zh' => '至少6位')).')</i></small></label>
    <input type="password" class="form-control" id="password" name="password" value="'.$password.'" required />
  </div>
  <div class="form-group" id="form-field-password_confirm">
    <label for="password_confirm">'.i18n(array('en' => 'Password again', 'zh' => '再次确认密码')).$mandatory_label.'</label>
    <input type="password" class="form-control" id="password_confirm" name="password_confirm" value="'.$password_confirm.'" required />
  </div>
  ' . (class_exists('SiteProfile') ? SiteProfile::renderUpdateForm($user, $exclude_fields) : '') 
    . (in_array('active', $exclude_fields) ? '' : $active_field) . '
  <div class="form-group" id="form-field-notice"><small><i>
    '.$mandatory_label.i18n(array(
        'en' => ' indicates mandatory fields',
        'zh' => ' 标记为必填项'
    )).'
  </i></small></div>
  <input type="submit" name="submit" class="btn btn-primary btn-block disabled" value="'.i18n(array(
      'en' => 'Signup',
      'zh' => '注册'
  )).'" />
  '.(module_enabled('form') ? Form::loadSpamToken('#signup', SITEUSER_FORM_SPAM_TOKEN) : '').'
</form>
';
    return $rtn;
  }
  
  
  static function findByUsername($username, $instance='SiteUser') {
    global $mysqli;
    $query = 'SELECT * FROM site_user WHERE username=' . DBObject::prepare_val_for_sql($username);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  static function findByEmail($email, $instance='SiteUser') {
    global $mysqli;
    $query = 'SELECT * FROM site_user WHERE email=' . DBObject::prepare_val_for_sql($email);
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new $instance();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  public function putPassword($password) {
    $this->setSalt(get_random_string(16));
    $this->setPassword(md5($this->getSalt().$password));
  }
  
  public function getProfile() {
    if (class_exists('SiteProfile')) {
      return SiteProfile::findByUId($this->getId());
    }
    return null;
  }
  
  static function renderLoginForm() {
    $rtn = Message::renderMessages() . '
<form role="form" action="'.uri('users/login', false).'" method="post" id="login">
  <fieldset>
    <div class="form-group">
      <label for="email">'.i18n(array('en' => 'E-mail or username', 'zh' => '电子邮件或者用户名')).'</label>
      <input class="form-control" name="email" id="email" autofocus required="">
    </div>
    <div class="form-group">
      <label for="password">'.i18n(array('en' => 'Password', 'zh' => '密码')).'</label>
      <input class="form-control" name="password" id="password" type="password" value="" required="">
    </div>
    <div class="form-group">
      <label>
      <input type="checkbox" name="remember" value="1" /> '.i18n(array('en' => 'Remember me', 'zh' => '记住我')).'
      </label>
    </div>
    <input type="submit" name="submit" class="btn btn-primary btn-block '.(module_enabled('form') ? 'disabled' : '').'" value="'.i18n(array('en' => 'Login', 'zh' => '登录')).'" />
    <small class="forget"><a href="'.uri('users/forget-password').'">'.i18n(array('en' => 'forget password?', 'zh' => '忘记密码了?')).'</a></small>
    '.(module_enabled('form') ? Form::loadSpamToken('#login', SITEUSER_FORM_SPAM_TOKEN) : '').'
  </fieldset>
</form>
';
    return $rtn;
  }
  
  public function checkPassword($password) {
    return md5($this->getSalt().$password) == $this->getPassword();
  }
  
  /**
   * Login the user and store in session and cookie
   */
  public function login($remember) {
    global $siteuser;
    
    self::getCurrentUser()->logout();
    
    $siteuser = $this;
    $_SESSION['siteuser_id'] = $this->getId();
    $_SESSION['siteuser_password'] = $this->getPassword();
    if ($remember) {
      setcookie('siteuser_id', $this->getid(), (time() + (3600 * 24 * 30)), '/' .  get_sub_root());
      setcookie('siteuser_password', $this->getPassword(), (time() + (3600 * 24 * 30)), '/' . get_sub_root());
    }
    
    $_SESSION['siteuser_permissions'] = array();
    foreach ($this->getPermissions() as $p) {
      $_SESSION['siteuser_permissions'][] = $p->getName();
    }
    $_SESSION['siteuser_roles'] = array();
    foreach ($this->getRoles() as $r) {
      $_SESSION['siteuser_roles'][] = $r->getName();
    }
  }
  
  public function isLogin() {
    $user = self::getCurrentUser();
    return $user->getId() == $this->getId();
  }


  public function logout() {
    global $siteuser;
    unset($siteuser);
    unset($_SESSION['siteuser_id']);
    unset($_SESSION['siteuser_password']);
    unset($_SESSION['siteuser_permissions']);
    unset($_SESSION['siteuser_roles']);
    setcookie('siteuser_id', null, time()-3600, '/' . get_sub_root());
    setcookie('siteuser_password', null, time()-3600, '/' . get_sub_root());
  }

  /**
   * get current user from session or cookie
   * 
   * @global type $siteuser
   * @return type
   */
  static function getCurrentUser() {
    $class = class_exists('MySiteUser') ? 'MySiteUser' : 'SiteUser';
    
    // try to get user from global var
    global $siteuser;
    if (isset($siteuser)) {
      return $siteuser;
    }
    
    // try to get user from session
    $uid = isset($_SESSION['siteuser_id']) ? $_SESSION['siteuser_id'] : null;
    if ($uid && $user = $class::findById($uid)) {
      $password = isset($_SESSION['siteuser_password']) ? $_SESSION['siteuser_password'] : 0;
      if ($user->getPassword() == $password) {
        $siteuser = $user;
        return $user;
      }
    }
    
    // try to get user from cookie
    $uid = isset($_COOKIE['siteuser_id']) ? $_COOKIE['siteuser_id'] : null;
    if ($uid && $user = $class::findById($uid)) {
      $password = isset($_COOKIE['siteuser_password']) ? $_COOKIE['siteuser_password'] : 0;
      if ($user->getPassword() == $password) {
        $siteuser = $user;
        $_SESSION['siteuser_id'] = $uid;
        $_SESSION['siteuser_password'] = $password;
        return $user;
      }
    }

    // if nothing succeeds in former attempts, return empty user
    $user = new $class();
    $user->setId(-1);
    $siteuser = $user;
    
    return $user;
  }
  
  public function getRoles() {
    $urs = SiteUserRole::findByUid($this->getId());
    $role_ids = array();
    foreach ($urs as $ur) {
      $role_ids[] = $ur->getRoleId();
    }
    
    global $mysqli;
    $query = "SELECT * FROM site_role WHERE id IN (".implode(',', $role_ids).")";
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new SiteRole();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  public function getPermissions() {
    $permissions = array();
    $pids = array();
    foreach ($this->getRoles() as $role) {
      foreach ($role->getPermissions() as $permission) {
        if (!in_array($permission->getId(), $pids)) {
          $pids[] = $permission->getId();
          $permissions[] = $permission;
        } else {
          continue;
        }
      }
    }
    return $permissions;
  }

  
  public function hasPermission($ps) {
    // get self permessions in an array()
    $permissions = array();
    foreach ($this->getPermissions() as $permission) {
      $permissions[] = $permission->getName();
    }
    
    // loop to check
    // for a group of permissions
    if (is_array($ps)) {
      foreach ($ps as $p) {
        if (!in_array($p, $permissions)) {
          return false;
        }
      }
      return true;
    // for a single permission
    } else {
      return in_array($ps, $permissions);
    }
  }
  
  public function hasRole($rs) {
    // get self permessions in an array()
    $roles = array();
    foreach ($this->getRoles() as $role) {
      $roles[] = $role->getName();
    }
    
    // loop to check
    // for a group of roles
    if (is_array($rs)) {
      foreach ($rs as $r) {
        if (!in_array($r, $roles)) {
          return false;
        }
      }
      return true;
    // for a single permission
    } else {
      return in_array($rs, $roles);
    }
  }
  
  static function renderForgetPasswordForm() {
    $rtn = Message::renderMessages() . '
<form role="form" action="" method="post" id="forget_password">
  <fieldset>
    <div class="form-group">
      <label for="email">'.i18n(array('en' => 'Your E-mail address', 'zh' => '您的电子箱地址')).'</label>
      <input class="form-control" name="email" type="email" id="email" autofocus required="">
    </div>
    <input type="submit" name="submit" class="btn btn-primary btn-block '.(module_enabled('form') ? 'disabled' : '').'" value="'.i18n(array('en' => 'Confirm', 'zh' => '确认')).'" />
    '.(module_enabled('form') ? Form::loadSpamToken('#forget_password', SITEUSER_FORM_SPAM_TOKEN) : '').'
  </fieldset>
</form>
';
    return $rtn;
  }
  
  public function sendPasswordResetEmail() {
    $html = new HTML();
    $content;
    if (is_file(MODULESROOT . '/site/templates/email/password_reset')) {
      $content = $html->render('site/email/password_reset', array(
         'user' => $this
     ));
    } else {
      $content = $html->render('siteuser/email/password_reset', array(
          'user' => $this
      ));
    }
    sendmail(i18n(array(
        'en' => 'Please reset your password',
        'zh' => '请重置您的密码'
    )), $content, $this->getEmail());
  }
  
  public function sendAccountActivationEmail() {
    $html = new HTML();
    $content;
    if (is_file(MODULESROOT . '/site/templates/email/account_activate')) {
      $content = $html->render('site/email/account_activate', array(
         'user' => $this
     ));
    } else {
      $content = $html->render('siteuser/email/account_activate', array(
          'user' => $this
      ));
    }
    sendmail(i18n(array(
        'en' => 'Activate your account',
        'zh' => '激活您的账号'
    )), $content, $this->getEmail());
  }
  
  public function delete() {
    
    // we delete profile as well
    if (module_enabled('siteuser_profile')) {
      $profile = $this->getProfile();
      $profile->delete();
    }
    
    return parent::delete();
    
  }
  
  static function renderPasswordResetForm() {
    $rtn = Message::renderMessages() . '
<form role="form" action="" method="post" id="forget_password_reset">
  <fieldset>
    <div class="form-group form-field-password">
      <label for="password">'.i18n(array('en' => 'Your new password', 'zh' => '您的新密码')).'</label>
      <input class="form-control" name="password" type="password" id="password" autofocus required="">
    </div>
    <div class="form-group form-field-password-confirm">
      <label for="password_confirm">'.i18n(array('en' => 'Confirm your password', 'zh' => '确认密码')).'</label>
      <input class="form-control" name="password_confirm" type="password" id="password_confirm" required="">
    </div>
    <input type="submit" name="submit" class="btn btn-primary btn-block '.(module_enabled('form') ? 'disabled' : '').'" value="'.i18n(array('en' => 'Update password', 'zh' => '更新密码')).'" />
    '.(module_enabled('form') ? Form::loadSpamToken('#forget_password_reset', SITEUSER_FORM_SPAM_TOKEN) : '').'
  </fieldset>
</form>
';
    return $rtn;
  }
}
