<div class="container" id="mainnav">
  <div class="row">
    <div class="col-xs-6 <?php echo preg_match('/^\/articles/', get_cur_page_url()) ? 'active' : '' ?> item">
      <a href="<?php echo uri('articles') ?>">文章</a>
    </div>
    <div class="col-xs-6 <?php echo preg_match('/^\/accounts/', get_cur_page_url()) ? 'active' : '' ?> item">
      <a href="<?php echo uri('accounts') ?>">公众号</a>
    </div>
  </div>
</div>