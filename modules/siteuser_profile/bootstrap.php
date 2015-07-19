<?php
define('AVATAR_DIR', FILE_DIR . DS . 'avatars');
if (!is_dir(AVATAR_DIR)) {
  mkdir(AVATAR_DIR);
}
// check avatar folder exist and writable
if (!is_writable(AVATAR_DIR)) {
  die('siteuser_profile module: Avatar folder needs to be writable.');
}