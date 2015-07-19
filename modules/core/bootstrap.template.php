


// register admin
$user = User::getInstance();
if (is_backend() && $user->isLogin()) {
  Backend::registerSideNav(
  '
  <li>
    <a href="#"><i class="fa fa-folder-open"></i> '.i18n(array(<?php foreach ($schema['form']['names'] as $lang => $name): ?>'<?php echo $lang ?>' => '<?php echo $name ?>',<?php endforeach; ?>)).'<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
      <li><a href="'.uri('admin/<?php echo $model ?>/list').'">'.i18n(array(
          'en' => 'List',
          'zh' => '列表'
      )).'</a></li>
      <li><a href="'.uri('admin/<?php echo $model ?>/create').'">'.i18n(array(
          'en' => 'Create',
          'zh' => '创建'
      )).'</a></li>
    </ul>
  </li>
  '        
  );
}