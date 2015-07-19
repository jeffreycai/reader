
require_once __DIR__ .'/../../../../bootstrap.php';
$rtn = new stdClass();

if (!User::getInstance()->isLogin()) {
  $rtn->error = i18n(array('en' => 'Authorisation required.', 'zh' => '抱歉，您没有权限进行此操作'));
  echo json_encode($rtn);
  exit;
}

$upload_dir = "<?php echo $upload_dir ?>";

if (isset($_FILES)) {
  $files = array();
  // create upload dir if not exist
  mkdir_recursively(WEBROOT . DS . $upload_dir);
//print_r($_FILES);
  foreach ($_FILES as $file) {
    $name = strtolower(preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file['name']));
    $tokens = explode('.', $name);
    $tokens[0] = $tokens[0] . '_' . rand(100, 999);
    $name = implode('.', $tokens);
    $type = $file['type'];
    $tmp_location = $file['tmp_name'];
    $error = $file['error'];
    $error_msg = null;
    $size = $file['size'];
    // validation
    if (!preg_match('/^image/', $type)) {
      $error_msg = i18n(array(
          'en' => 'Upload file needs to be an image file',
          'zh' => '上传文件需为图片文件'
      ));
    } else if ($size > (1 * 1000 * 1000)) {
      $error_msg = i18n(array(
          'en' => 'Max upload file size should be less than',
          'zh' => '最大上传文件应小于'
      )) . ' 1MB';
    }
    if ($error_msg) {
      $rtn->error = $error_msg;
    } else {
      load_library_wide_image();
      $dest_location = WEBROOT . DS . $upload_dir . DS . $name;
      
<?php if ($transform): ?>
      try {
        $image = WideImage::load($tmp_location);
        unlink($tmp_location);
        $refill = <?php if ($refill): ?>"<?php echo $refill ?>"<?php else: ?>false<?php endif; ?>;
        $watermark = <?php if ($watermark): ?><?php echo "WEBROOT . DS . '$watermark'" ?><?php else: ?>false<?php endif; ?>;
<?php if ($dimension): ?>
        if ($refill) {
          $bgcolor = $image->allocateColor(<?php echo $refill ?>);
          $image = $image->resize(<?php echo $dimension[0] ?>, <?php echo $dimension[1] ?>, 'inside')->resizeCanvas(<?php echo $dimension[0] ?>, <?php echo $dimension[1] ?>, 'center', 'center', $bgcolor);
        } else {
          $image = $image->resize(<?php echo $dimension[0] ?>, <?php echo $dimension[1] ?>, 'outside')->resizeCanvas(<?php echo $dimension[0] ?>, <?php echo $dimension[1] ?>, 'center', 'center');
        }
<?php endif; ?>

        if ($watermark) {
          $watermark = WideImage::load(WEBROOT . DS . "<?php echo $watermark ?>");
          $image = $image->merge($watermark, 'right-10', 'bottom-10', 50);
        }
        $image->saveToFile($dest_location);

        $rtn->uri = "$upload_dir/" . $name;
      } catch (Exception $e) {
        $rtn->error = 'WideImage error: ' . $e->getMessage();
      }
<?php else: ?>
      if (move_uploaded_file($tmp_location, $dest_location)) {
        $rtn->uri = "$upload_dir/" . $name;
      } else {
        $rtn->error = i18n(array('en' => 'Failed to move upload file', 'zh' => '移动上传文件失败'));
      }
<?php endif; ?>
    }
    
    echo json_encode($rtn);
    exit;
  }
}