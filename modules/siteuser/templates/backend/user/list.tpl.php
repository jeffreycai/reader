<?php 
$start_entry = ($current_page - 1)*$per_page;
$end_entry = min(array($total, $current_page*$per_page));
?>

<div id="page-wrapper">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'User', 'zh' => '用户')); ?></h1>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'Manage user', 'zh' => '管理用户')) ?></div>
        <div class="panel-body">
          
        <?php echo Message::renderMessages(); ?>
          
<div style="margin-bottom: 10px;">
  <a href="<?php echo uri('admin/user/add'); ?>"><i class="fa fa-plus-circle"></i> <?php echo i18n(array(
      'en' => 'Add new user',
      'zh' => '添加新用户'
  )) ?></a>
  <br />
</div>
          
<table class="table table-striped table-bordered table-hover dataTable no-footer">
  <thead>
      <tr role="row">
        <th><?php i18n_echo(array('en' => 'ID', 'zh' => 'ID')) ?></th>
        <th><?php i18n_echo(array('en' => 'Username', 'zh' => '用户名')) ?></th>
        <th><?php i18n_echo(array('en' => 'Active?', 'zh' => '已激活?')) ?></th>
        <th><?php i18n_echo(array('en' => 'EAct?', 'zh' => '邮激活?')) ?></th>
        <th><?php i18n_echo(array('en' => 'Roles', 'zh' => '角色')) ?></th>
        <th><?php i18n_echo(array('en' => 'Email', 'zh' => '邮箱')) ?></th>
        <th><?php i18n_echo(array('en' => 'Created at', 'zh' => '创建于')) ?></th>
        <th><?php i18n_echo(array('en' => 'Last login', 'zh' => '最后登录')) ?></th>
        <th><?php i18n_echo(array('en' => 'Actions', 'zh' => '操作')) ?></th>
      </tr>
  </thead>
  <tbody>
    <?php 
    foreach ($users as $user): 
    ?>
    <tr>
      <td><?php echo $user->getId() ?></td>
      <td><?php echo $user->getUsername() ?></td>
      <td><i class="fa fa-<?php echo $user->getActive() ? 'check' : 'times' ?>"></i></td>
      <td><i class="fa fa-<?php echo $user->getEmailActivated() ? 'check' : 'times' ?>"></i></td>
      <td>
        <?php 
        $roles = array();
        foreach ($user->getRoles() as $role) {
          $roles[] = $role->getName();
        }
        echo implode(',', $roles);
        ?>
      </td>
      <td><?php echo $user->getEmail() ?></td>
      <td><?php echo date('Y-m-d', $user->getCreatedAt()) ?></td>
      <td><?php echo $user->getLastLogin() ? date('Y-m-d', $user->getLastLogin()) : 'N/A' ?></td>
      <td>
        <a class="edit" href="<?php print_uri('admin/user/edit/' . $user->getId()) ?>"><small><?php i18n_echo(array('en' => 'Edit', 'zh' => '编辑')) ?></small></a> /
        <a class="delete" href="<?php print_uri('admin/user/delete/' . $user->getId()); ?>"><small><?php i18n_echo(array('en' => 'Delete', 'zh' => '删除')) ?></small></a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
          
<script type="text/javascript">
  $(".delete").click(function(){
    var answer = confirm("<?php echo i18n(array(
        'en' => 'Do you want to delete user - ' . $user->getUsername() . '?',
        'zh' => '你确定删除用户' . $user->getUsername() . '吗?'
    )) ?>");
    if (answer) {
      return true;
    }
    return false;
  });
</script>

<div class="row">
  <div class="col-sm-12" style="text-align: right;">
  <?php i18n_echo(array(
      'en' => 'Showing ' . $start_entry . ' to ' . $end_entry . ' of ' . $total . ' entries', 
      'zh' => '显示' . $start_entry . '到' . $end_entry . '条记录，共' . $total . '条记录',
  )); ?>
  </div>
  <div class="col-sm-12" style="text-align: right;">
  <?php echo $pager; ?>
  </div>
</div>
          
        </div>
      </div>
    </div>
  </div>
</div>