<?php

if (isset($_POST['submit'])) {
  $nickname = isset($_POST['nickname']) ? strip_tags(trim($_POST['nickname'])) : null;
  $avatar   = isset($_FILES['avatar'])  ?                $_FILES['avatar']     : null;
}

// nickname
if (is_null($nickname)) {
  $messages[] = new Message(Message::DANGER, i18n(array(
              'en' => 'Please enter your nickname',
              'zh' => '请填写昵称'
  )));
}

// avatar
if ($avatar) {
  switch ($avatar['error']) {
    case  UPLOAD_ERR_OK:
       if ($avatar && !preg_match('/^image/', $avatar['type'])) {
        $messages[] = new Message(Message::DANGER, i18n(array(
                    'en' => 'Please select an image file as avatar',
                    'zh' => '请选择图片文件作为头像上传'
        )));
      } else if ($avatar['size'] > $settings['profile']['avatar_max_size']) {
        $messages[] = new Message(Message::DANGER, i18n(array(
                    'en' => 'Avatar file size can not be larger than ' . round($settings['profile']['avatar_max_size'] / 1000000) . 'MB',
                    'zh' => '头像图片大小不能超过'  . round($settings['profile']['avatar_max_size'] / 1000000, 1) . 'MB'
        )));
      }
      break;
    case  UPLOAD_ERR_NO_FILE:
      break;
    case UPLOAD_ERR_INI_SIZE:
      $messages[] = new Message(Message::DANGER, i18n(array(
                  'en' => 'Avatar file size can not be larger than ' . round($settings['profile']['avatar_max_size'] / 1000000) . 'MB',
                  'zh' => '头像图片大小不能超过'  . round($settings['profile']['avatar_max_size'] / 1000000, 1) . 'MB'
      )));
      break;
    default:
      $messages[] = new Message(Message::DANGER, i18n(array(
                  'en' => 'Error occured during upload. Please try again',
                  'zh' => '头像图片上传错误，请重试'
      )));
      break;
  }
}