<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'User', 'zh' => '用户')); ?></h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'Manage permission', 'zh' => '管理权限')) ?></div>
        <div class="panel-body">
          
        <?php echo Message::renderMessages(); ?>
<form action="" method="POST">
  <table class="table table-bordered table-hover dataTable no-footer">
    <thead>
        <tr role="row">
          <th></th>
          <?php foreach ($roles as $role): ?>
          <th><?php echo $role->getName() ?></th>
          <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
      <?php foreach ($permissions as $permission): ?>
      <tr>
        <th><?php echo $permission->getName() ?></th>
        <?php foreach ($roles as $r): ?>
        <td><input type="checkbox" value="1" name="role_<?php echo $r->getId() ?>[<?php echo $permission->getId() ?>]" <?php echo $r->hasPermission($permission->getName()) ? 'checked="checked"' : '' ?>/></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
  <input type="submit" class="btn btn-primary" name="submit" value="<?php echo i18n(array(
    'en' => 'Submit',
    'zh' => '提交'
  )) ?>" />
</form>
          
        </div>
      </div>
    </div>
  </div>
</div>