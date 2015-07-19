<div id="page-wrapper">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-header">[[[ i18n_echo(array(
<?php foreach ($model_names as $lang => $name): ?>
        '<?php echo $lang ?>' => '<?php echo $name ?>',
<?php endforeach; ?>
      )); ]]]</h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">[[[ i18n_echo(array(
            'en' => 'Edit', 
            'zh' => '编辑'
        )) ]]]</div>
        <div class="panel-body">
          
        [[[ echo Message::renderMessages(); ]]]
          
<form role="form" method="POST" action="[[[ echo uri('admin/<?php echo $model ?>/edit/' . $object->getId()) ]]]">
<?php foreach ($form_fields as $field => $settings): $widget = new $settings['widget_class']($field, $settings['widget_conf']); ?>
  <?php echo $widget->render($module, $model); ?>
<?php endforeach; ?>

  <input type="submit" name="submit" value="[[[ i18n_echo(array(
      'en' => 'Edit', 
      'zh' => '编辑'
  )) ]]]" class="btn btn-default">
</form>
          
        </div>
      </div>
    </div>
  </div>
</div>

