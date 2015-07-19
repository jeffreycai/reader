<?php

$profile = $user->getProfile();
if (is_null($profile)) {
  $profile = new SiteProfile();
  $profile->setUserId($user->getId());
}
$profile->setNickname($nickname);

if ($avatar && $avatar['error'] == UPLOAD_ERR_OK) {
  load_library_wide_image();
  $image = WideImage::load($avatar['tmp_name']);
  $image = $image->resize($settings['profile']['avatar_width'], $settings['profile']['avatar_height']);
  $white = $image->allocateColor(255, 255, 255);
  $image = $image->resizeCanvas($settings['profile']['avatar_width'], $settings['profile']['avatar_height'], 'center', 'center', $white);
  $image->saveToFile(AVATAR_DIR . '/' . $user->getUsername() . '.jpg', 80);
  $profile->setThumbnail($user->getUsername() . '.jpg');
  
  @unlink($avatar['tmp_name']);
}

$profile->save();