
<div id="page-wrapper">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'Maintenance mode', 'zh' => '系统维护模式')); ?></h1>
    </div>
  </div>
  <?php echo Message::renderMessages(); ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'Turn on/off maintenance mode', 'zh' => '设置开启系统维护模式')) ?></div>
        <div class="panel-body">
          <form action="" method="POST">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="switch" value="1" <?php if ($maintenance == 1): ?>checked="checked"<?php endif; ?> /> <?php echo i18n(array(
                    'en' => 'Maintenance mode',
                    'zh' => '系统维护模式开关'
                )) ?>
              </label>
            </div>
            <input type="submit" class="btn btn-default" name="submit" value="<?php echo i18n(array(
                'en' => 'Submit',
                'zh' => '提交'
            )) ?>" />
          </form>
        </div>
      </div>
    </div>
  </div>
</div>