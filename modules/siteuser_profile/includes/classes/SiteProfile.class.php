<?php
require_once "BaseSiteProfile.class.php";

class SiteProfile extends BaseSiteProfile {
  static function renderUpdateForm(SiteUser $user = null, $exclude_fields = array()) {
    $settings = Vars::getSettings();
    
    $profile = $user ? $user->getProfile() : null;

    // get vars from form submission
    $nickname = isset($_POST['nickname'])  ? strip_tags($_POST['nickname'])  : (isset($profile) ? $profile->getNickname()  : '');
    
    $mandatory_label = ' <span style="color: rgb(185,2,0); font-weight: bold;">*</span>';
    $avatar_field = '
  <div class="form-group" id="form-field-avatar" >
    <label for="avatar">'.i18n(array('en' => 'Avatar', 'zh' => '头像')).' <small style="font-weight: normal;"><i>('.  i18n(array(
        'en' => 'optional',
        'zh' => '可选'
    )).')</i></small></label>
    '.( $profile ? "<div><img src='" . $profile->getThumbnailUrl() . "' alt='" . $user->getUsername() . "' style='cursor: pointer;' /></div>" : '').'
    <input type="file" id="avatar" name="avatar"' .($profile ?  ' style="display: none;"' : '') . ' />
    <small>'.  i18n(array(
        'en' => 'Max image file size: ' . round($settings['profile']['avatar_max_size'] / 1000000, 1) . 'MB',
        'zh' => '最大图片上传尺寸： ' . round($settings['profile']['avatar_max_size'] / 1000000, 1) . 'MB'
    )).'</small>
  </div>
';
    $rtn = '
  <div class="form-group" id="form-field-nickname">
    <label for="nickname">'.i18n(array('en' => 'Nick name', 'zh' => '昵称')).$mandatory_label.' <small style="font-weight: normal;"><i>('.i18n(array(
        'en' => 'what others see you as',
        'zh' => '其他用户看到的您的称呼'
    )).')</i></small></label>
    <input type="text" class="form-control" id="nickname" name="nickname" value="'.$nickname.'" required placeholder="" />
  </div>' . (in_array('avatar', $exclude_fields) ? '' : $avatar_field) . '
  <script type="text/javascript">
    $("#form-field-avatar img").click(function(){
      $("#avatar").trigger("click");
    });
    $("#avatar").change(function(){
      //$("#form-field-avatar img").fadeOut();
      $(this).fadeIn();
    });
  </script>
';
    return $rtn;
  }
  
  public static function findByUId($uid) {
    global $mysqli;
    $query = 'SELECT * FROM site_profile WHERE user_id=' . $uid;
    $result = $mysqli->query($query);
    if ($result && $b = $result->fetch_object()) {
      $obj = new SiteProfile();
      DBObject::importQueryResultToDbObject($b, $obj);
      return $obj;
    }
    return null;
  }
  
  public function getThumbnailUrl() {
    if ($this->getThumbnail()) {
      return get_sub_root() . "/files/avatars/" . $this->getThumbnail();
    }
    $settings = Vars::getSettings();
    return get_sub_root() . "/files/avatars/" . $settings['profile']['avatar_default'];
  }
  
  public function delete() {
    // we delete avatar image first
    @unlink(AVATAR_DIR . '/' . $this->getThumbnail());
    
    parent::delete();
  }
}
