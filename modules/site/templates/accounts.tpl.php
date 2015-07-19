<div class="container" id="body">
  <div class="row">
<?php foreach ($accounts as $account): $account = $account->getWechatAccount();
  if ($account->getActive() == 0){continue;} ?>
    <div class="col-xs-6 col-sm-4">
      <div class="tile">
        <div class="thumb">
          <img src="<?php echo $account->getQrCode() ?>" alt="<?php echo $account->getNickname() ?>" />
        </div>
        <div class="details">
          <h2><?php echo $account->getNickname() ?></h2>
        </div>
      </div>
    </div>
<?php endforeach ?>
  </div>
</div>