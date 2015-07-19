<p>
  <?php echo i18n(array(
      'en' => 'Please click the link below to reset your password:',
      'zh' => '请点击以下链接重置您的密码：'
  )); 
  $url = uri('user/'.$user->getId().'/forget-password/reset/'.encrypt($user->getSalt()), false);
  $ssl = !empty($s['HTTPS']) && $s['HTTPS'] == 'on';
  ?>
</p>
<p><a href="http<?php echo $ssl ? 's' : '' ?>://<?php echo $_SERVER["HTTP_HOST"] . $url; ?>">http<?php echo $ssl ? 's' : '' ?>://<?php echo $_SERVER["HTTP_HOST"] . $url ?></a></p>