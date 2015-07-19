

<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">[[[ i18n_echo(array(<?php foreach ($model_names as $lang => $name): ?>'<?php echo $lang ?>' => '<?php echo $name ?>',<?php endforeach ?>)); ]]]</h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading">[[[ i18n_echo(array('en' => 'List', 'zh' => '列表')) ]]]</div>
        <div class="panel-body">
          
        [[[ echo Message::renderMessages(); ]]]
          
<table class="table table-striped table-bordered table-hover dataTable no-footer">
  <thead>
      <tr role="row">
        <?php foreach ($fields as $field): ?>
        <th><?php echo $field ?></th>
        <?php endforeach; ?>
        <th>Actions</th>
      </tr>
  </thead>
  <tbody>
    [[[ foreach ($objects as $object): ]]]
    <tr>
      <?php foreach ($fields as $field): ?>
      <td>[[[ echo $object->get<?php echo format_as_class_name($field) ?>() ]]]</td>
      <?php endforeach; ?>
      <td>
        <div class="btn-group">
          <a class="btn btn-default btn-sm" href="[[[ echo uri('admin/<?php echo $model ?>/edit/' . $object->getId()); ]]]"><i class="fa fa-edit"></i></a>
          <a onclick="return confirm('[[[ echo i18n(array('en' => 'Are you sure to delete this record ?', 'zh' => '你确定删除这条记录吗 ?')); ]]]');" class="btn btn-default btn-sm" href="[[[ echo uri('admin/<?php echo $model ?>/delete/' . $object->getId(), false); ]]]"><i class="fa fa-remove"></i></a>
        </div>
      </td>
    </tr>
    [[[ endforeach; ]]]
  </tbody>
</table>

<div class="row">
  <div class="col-sm-12" style="text-align: right;">
  [[[ i18n_echo(array(
      'en' => 'Showing ' . $start_entry . ' to ' . $end_entry . ' of ' . $total . ' entries', 
      'zh' => '显示' . $start_entry . '到' . $end_entry . '条记录，共' . $total . '条记录',
  )); ]]]
  </div>
  <div class="col-sm-12" style="text-align: right;">
  [[[ echo $pager; ]]]
  </div>
</div>
          
        </div>
      </div>
    </div>
  </div>
</div>