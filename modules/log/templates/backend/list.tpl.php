<?php 
$start_entry = ($current_page - 1)*$settings['log']['backend_per_page'] + 1;
$end_entry = min(array($total, $current_page*$settings['log']['backend_per_page']));
?>

<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'System log', 'zh' => '系统日志')); ?></h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'System log list', 'zh' => '系统日志列表')) ?></div>
        <div class="panel-body">
          
        <?php echo Message::renderMessages(); ?>
          
          <div>
            <a href="<?php echo uri('admin/log/empty', false) ?>" onclick="return confirm('Empty whole log?');">Empty log</a>
            <br />
          </div>
          
<table class="table table-striped table-bordered table-hover dataTable no-footer">
  <thead>
      <tr role="row">
        <th><?php i18n_echo(array('en' => 'Time', 'zh' => '时间')) ?></th>
        <th><?php i18n_echo(array('en' => 'Module', 'zh' => '模块')) ?></th>
        <th><?php i18n_echo(array('en' => 'Category', 'zh' => '类别')) ?></th>
        <th><?php i18n_echo(array('en' => 'IP', 'zh' => 'IP')) ?></th>
        <th><?php i18n_echo(array('en' => 'Content', 'zh' => '内容')) ?></th>
      </tr>
  </thead>
  <tbody>
    <?php foreach ($logs as $log): ?>
    <tr>
      <td><?php echo date('Y-m-d H:i:s', $log->getTime()); ?></td>
      <td><?php echo $log->getModule() ?></td>
      <td><span class="label label-<?php echo str_replace("notice", "info", str_replace("error", "danger", strtolower($log->getCategory()))) ?>"><?php echo $log->getCategory(); ?></span></td>
      <td><?php echo $log->getIp(); ?></td>
      <td><?php echo $log->getContent(); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

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