<p><?php echo i18n(array(
    'en' => 'Welcome onboard!',
    'zh' => '欢迎！'
)) ?></p>

<p>
  <?php echo i18n(array(
      'en' => 'Thank you for registering with us. Please click the link below to activate your account:',
      'zh' => '感谢您的注册，请点击下面的链接激活您的账号'
  )); 
  $url = uri('user/'.$user->getId().'/activate/'.encrypt($user->getSalt()), false);
  $ssl = !empty($s['HTTPS']) && $s['HTTPS'] == 'on';
  ?>
</p>
<p><a href="http<?php echo $ssl ? 's' : '' ?>://<?php echo $_SERVER["HTTP_HOST"] . $url; ?>">http<?php echo $ssl ? 's' : '' ?>://<?php echo $_SERVER["HTTP_HOST"] . $url ?></a></p>