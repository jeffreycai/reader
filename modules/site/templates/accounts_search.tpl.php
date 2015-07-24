<div class="container" id="body">
<?php if ($articles): ?>
<?php foreach($articles as $article):
  
  $subscribed = false;
  if ($wechat_account = WechatAccount::findByOpenid($article['openid'])) {
    if (UserWechatAccount::findByWechatAccountId($wechat_account->getId())) {
      $subscribed = true;
    }
  }
  
  
?>
  <article class="row article">
    <div class="col-xs-12 content">
      <div class="inner">
        <div class="left">
          <img class="lazyloading" src="<?php echo uri('modules/site/assets/images/ajax-loader-2.gif') ?>" data-source="<?php echo $article['thumb'] ?>" />
        </div>
        <div class="middle">
          <?php if (isset($article['title'])): ?>
          <h2><?php echo $article['title'] ?></h2>
          <?php endif; ?>
          <?php if (isset($article['account'])): ?>
          <p class="account">微信号: <?php echo $article['account'] ?></p>
          <?php endif; ?>
          <?php if (isset($article['description'])): ?>
          <p class="description"><?php echo $article['description'] ?></p>
          <?php endif; ?>
          <?php if (isset($article['certification'])): ?>
          <p class="certification"><?php echo $article['certification'] ?></p>
          <?php endif; ?>
          <?php if (isset($article['latest'])): ?>
          <p class="latest"><?php echo $article['latest'] ?></p>
          <?php endif; ?>
        </div>
        <div class="right">
          <button class="<?php echo $subscribed ? 'un' : '' ?>subscribe btn btn-default btn-sm"
                  data-openid="<?php echo $article['openid'] ?>"
                  data-wechatid="<?php echo $article['account'] ?>"
                  data-logo="<?php echo htmlentities($article['thumb']) ?>"
                  data-nickname="<?php echo $article['title'] ?>"
                  data-qrcode="<?php echo htmlentities($article['qrcode']) ?>"
                  data-description="<?php if (isset($article['description'])): ?><?php echo htmlentities($article['description']) ?><?php endif ?>" 
                  data-certification="<?php if (isset($article['certification'])): ?><?php echo htmlentities($article['certification']) ?><?php endif ?>"
          ><span class="glyphicon glyphicon-<?php echo $subscribed ? 'minus' : 'plus' ?>"></span> <?php echo $subscribed ? '退订' : '订阅' ?></button>
        </div>
      </div>
    </div>
  </article>
<?php endforeach; ?>
<?php endif; ?>
  
  <div class="row">
    <div class="col-xs-12">
      <?php echo $pager ?>
    </div>
  </div>
  
<?php if (is_null($articles)): ?>
  <div class="row">
    <div class="col-xs-12">
      <p style="text-align: center;">请输入关键字搜索公众号</p>
    </div>
  </div>
<?php elseif (sizeof($articles) == 0): ?>
  <div class="row">
    <div class="col-xs-12">
      <p style="text-align: center;">没有搜索到任何公众号，请再试试其他关键字</p>
    </div>
  </div>
<?php endif; ?>
</div>


<script>
  jQuery(function(){
    $('.right').on('click', '.subscribe', function(){
      var button = $(this);
      var html = $(this).html();
      $(this).addClass('disabled').html('<img src="<?php echo uri('modules/site/assets/images/ajax-loader.gif') ?>" alt="loading" />');
      $.post('<?php echo uri('accounts/add') ?>',{
        openid: $(this).data('openid'),
        wechatid: $(this).data('wechatid'),
        logo: $(this).data('logo'),
        nickname: $(this).data('nickname'),
        qrcode: $(this).data('qrcode'),
        description: $(this).data('description'),
        certification: $(this).data('certification')
      }, function(data){
        if (data.status == 'success') {
          button.removeClass('disabled subscribe').addClass('unsubscribe').html('<span class="glyphicon glyphicon-minus"></span> 退订');
        } else if (data.status == 'error') {
          sweetAlert('Oops', data.message, 'error');
          button.removeClass('disabled').html(html);
        } else {
          sweetAlert('Oops', '系统出错，操作失败 :(', 'error');
          button.removeClass('disabled').html(html);
        }
      }, 'json');
    });
    $('.right').on('click', '.unsubscribe', function(){
      var button = $(this);
      var html = $(this).html();
      $(this).addClass('disabled').html('<img src="<?php echo uri('modules/site/assets/images/ajax-loader.gif') ?>" alt="loading" />');
      $.post('<?php echo uri('accounts/remove') ?>',{
        openid: $(this).data('openid')
      }, function(data){
        if (data.status == 'success') {
          button.removeClass('disabled unsubscribe').addClass('subscribe').html('<span class="glyphicon glyphicon-plus"></span> 订阅');
        } else if (data.status == 'error') {
          sweetAlert('Oops', data.message, 'error');
          button.removeClass('disabled').html(html);
        } else {
          sweetAlert('Oops', '系统出错，操作失败 :(', 'error');
          button.removeClass('disabled').html(html);
        }
      }, 'json');
    });
  });
</script>