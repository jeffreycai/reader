<div id="page-wrapper">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'User', 'zh' => '用户')); ?></h1>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'Add user', 'zh' => '添加用户')) ?></div>
        <div class="panel-body">
          
        <?php echo Message::renderMessages(); ?>
           
        <?php echo SiteUser::renderUpdateFormBackend(); ?>

        </div>
      </div>
    </div>
  </div>
</div>