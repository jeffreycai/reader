
$id = isset($vars[1]) ? $vars[1] : null;
$object = <?php echo $model_class ?>::findById($id);

$error_flag = false;
if ($object) {
  if ($object->delete()) {
    Message::register(new Message(Message::SUCCESS, i18n(array(
      'en' => 'Record deleted',
      'zh' => '记录删除成功'
    ))));
  } else {
    $error_flag = true;
  }
} else {
  $error_flag = true;
}

if ($error_flag) {
  Message::register(new Message(Message::DANGER, i18n(array(
    'en' => 'Record deletion failed',
    'zh' => '记录删除失败'
  ))));
}

HTML::forwardBackToReferer();