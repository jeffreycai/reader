<div class="container" id="body">
  <div class="row">
<?php foreach ($accounts as $account): $account = $account->getWechatAccount();
  if ($account->getActive() == 0){continue;} ?>
    <div class="col-xs-6 col-sm-4">
      <div class="tile">
        <div class="thumb">
          <a href="<?php echo uri('account/' . $account->getOpenid()) ?>"><img class="lazyloading" src="<?php echo uri('modules/site/assets/images/ajax-loader-2.gif') ?>" data-source="<?php echo $account->getLogo() ?>" /></a>
        </div>
        <div class="details">
          <h2><a href="<?php echo uri('account/' . $account->getOpenid()) ?>"><?php echo $account->getNickname() ?></a></h2>
        </div>
      </div>
    </div>
<?php endforeach ?>
  </div>
</div>