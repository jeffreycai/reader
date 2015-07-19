
$id = isset($vars[1]) ? $vars[1] : null;
$object = <?php echo $model_class ?>::findById($id);
if (is_null($object)) {
  HTML::forward('core/404');
}

// handle form submission
if (isset($_POST['submit'])) {
  $error_flag = false;

  /// validation
<?php foreach ($form_fields as $field => $settings): $widget = new $settings['widget_class']($field, $settings['widget_conf']); ?>
  <?php echo $widget->validate(); ?>
<?php endforeach; ?>
  /// proceed submission
<?php foreach ($form_fields as $field => $settings): $widget = new $settings['widget_class']($field, $settings['widget_conf']); ?>
  <?php echo $widget->proceed(); ?>
<?php endforeach; ?>
  if ($error_flag == false) {
    if ($object->save()) {
      Message::register(new Message(Message::SUCCESS, i18n(array("en" => "Record saved", "zh" => "记录保存成功"))));
      HTML::forwardBackToReferer();
    } else {
      Message::register(new Message(Message::DANGER, i18n(array("en" => "Record failed to save", "zh" => "记录保存失败"))));
    }
  }
}



$html = new HTML();

$html->renderOut('core/backend/html_header', array(
  'title' => i18n(array(
<?php foreach ($model_names as $lang => $name): ?>
  '<?php echo $lang ?>' => 'Edit <?php echo $name ?>',
<?php endforeach; ?>
  )),
));
$html->output('<div id="wrapper">');
$html->renderOut('core/backend/header');


$html->renderOut('<?php echo $module ?>/backend/<?php echo $model ?>_edit', array(
  'object' => $object
));


$html->output('</div>');

$html->renderOut('core/backend/html_footer');

exit;

