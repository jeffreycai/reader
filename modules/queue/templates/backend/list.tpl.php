<?php 
$start_entry = ($current_page - 1)*$backend_perpage + 1;
$end_entry = min(array($total, $current_page*$backend_perpage));
?>

<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><?php i18n_echo(array('en' => 'Queue', 'zh' => '队列')); ?></h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array('en' => 'Task list', 'zh' => '任务列表')) ?></div>
        <div class="panel-body">
          
          <div class="form-group">
            <form action="<?php echo uri('admin/queue/empty', false) ?>" method="POST">
              <input type="text" name="type" placeholder="<?php echo i18n(array('en' => 'Type', 'zh' => '类型')) ?>" />
              <button type="submit" class="btn btn-sm btn-default" href="<?php echo uri('admin/queue/') ?>" onclick="return confirm('Are you sure to purge queue?');"><?php echo i18n(array(
                'en' => 'Purge',
                'zh' => '清除'
            )) ?></button>
            </form>
          </div>
          
        <?php echo Message::renderMessages(); ?>
          
<table class="table table-striped table-bordered table-hover dataTable no-footer">
  <thead>
      <tr role="row">
        <th><?php i18n_echo(array('en' => 'Status', 'zh' => '状态')) ?></th>
        <th><?php i18n_echo(array('en' => 'Priority', 'zh' => '优先级')) ?></th>
        <th><?php i18n_echo(array('en' => 'Status Info', 'zh' => '状态信息')) ?></th>
        <th><?php i18n_echo(array('en' => 'Type', 'zh' => '类型')) ?></th>
        <th><?php i18n_echo(array('en' => 'Created', 'zh' => '创建时间')) ?></th>
        <th><?php i18n_echo(array('en' => 'Started', 'zh' => '开始时间')) ?></th>
        <th><?php i18n_echo(array('en' => 'Finished', 'zh' => '结束时间')) ?></th>
        <th><?php i18n_echo(array('en' => 'Time consumed', 'zh' => '耗时')) ?></th>
        <th><?php i18n_echo(array('en' => 'Function', 'zh' => '函数')) ?></th>
        <th><?php i18n_echo(array('en' => 'Description', 'zh' => '描述')) ?></th>
        <th><?php i18n_echo(array('en' => 'Actions', 'zh' => '操作')) ?></th>
      </tr>
  </thead>
  <tbody>
    <?php foreach ($queues as $q): ?>
    <tr id="queue_<?php echo $q->getId() ?>">
      <td>
        <?php switch($q->getStatus()):
        case Queue::STATUS_QUEUED:
          $class = 'default';
          $text = i18n(array('en' => 'queued', 'zh' => '排队中'));
          break;
        case Queue::STATUS_INPROGRESS:
          $class = 'primary';
          $text = i18n(array('en' => 'proceeding', 'zh' => '执行中'));
          break;
        case Queue::STATUS_ABORTED:
          $class = 'warning';
          $text = i18n(array('en' => 'aborted', 'zh' => '中断'));
          break;
        case Queue::STATUS_SUCCESS:
          $class = 'success';
          $text = i18n(array('en' => 'success', 'zh' => '成功'));
          break;
        case Queue::STATUS_FAIL:
          $class = 'danger';
          $text = i18n(array('en' => 'failed', 'zh' => '失败'));
          break;
        endswitch; ?>
        <span class="label label-<?php echo $class ?>"><?php echo $text ?></span>
      </td>
      <td><?php echo $q->getPriorityLiteral() ?></td>
      <td><?php echo $q->getStatusInfo() ?></td>
      <td><?php echo $q->getType() ?></td>
      <td><?php echo !($q->getCreatedAt()) ? 'Null' : date('y-m-d H:i:s', $q->getCreatedAt()) ?></td>
      <td><?php echo !($q->getStartedAt()) ? 'Null' : date('y-m-d H:i:s', $q->getStartedAt()) ?></td>
      <td><?php echo !($q->getFinishedAt()) ? 'Null' : date('y-m-d H:i:s', $q->getFinishedAt()) ?></td>
      <td><?php echo $q->getStartedAt() && $q->getFinishedAt() ? ($q->getFinishedAt() - $q->getStartedAt()) : '' ?></td>
      <td><?php echo $q->getFunction() ?></td>
      <td><?php echo $q->getDescription() ?></td>
      <td>
        <div class="btn-group">
          <a href="<?php echo uri('admin/queue/'.$q->getId().'/run') ?>" class='btn btn-sm btn-primary'><i class='fa fa-play-circle-o'></i></a>
          <a href="<?php echo uri('admin/queue/'.$q->getId().'/delete') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sure to delete?');"><i class="fa fa-remove"></i></a>
        </div>
      </td>
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