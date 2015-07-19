<div id="header">
  <div class="container">
    <a class="goback" href="<?php echo gobackurl(); ?>"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <div class="search">
      <form method="GET" action="<?php echo uri('accounts/search') ?>">
        <input name="keyword" placeholder="搜索公众号" required />
        <button><span class="glyphicon glyphicon-search"></span></button>
      </form>
    </div>
  </div>
</div>

<script>
  // auto focus on search bar
  $('#header .search input').focus();
</script>