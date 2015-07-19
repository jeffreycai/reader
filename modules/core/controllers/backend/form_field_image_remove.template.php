
require_once __DIR__ .'/../../../../bootstrap.php';

if (!User::getInstance()->isLogin()) {
  $rtn->error = i18n(array('en' => 'Authorisation required.', 'zh' => '抱歉，您没有权限进行此操作'));
  echo json_encode($rtn);
  exit;
}

$file_path = WEBROOT . DS . get_sub_root() . $_GET['path'];
$rtn = new stdClass();

if (is_file($file_path)) {
  if (unlink($file_path)) {
    $rtn->success = 1;
  } else {
    $rtn->error = i18n(array('en' => 'Image file exists but failed to be removed.', 'zh' => '图片文件存在，但删除失败'));
  }
} else {
  $rtn->error = i18n(array('en' => 'Image file does not exist', 'zh' => '图片文件不存在'));
}

echo json_encode($rtn);
exit;
