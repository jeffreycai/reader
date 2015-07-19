<div id="page-wrapper">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-header"><?php i18n_echo(array(
        'en' => 'Wechat Account',
        'zh' => '微信公共账号',
      )); ?></h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><?php i18n_echo(array(
            'en' => 'Create', 
            'zh' => '创建'
        )) ?></div>
        <div class="panel-body">
          
        <?php echo Message::renderMessages(); ?>
          
<form role="form" method="POST" action="<?php echo uri('admin/wechat_account/create') ?>">
  
<div class='form-group'>
  <label for='nickname'>nickname</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['nickname']) ? strip_tags($_POST['nickname']) : '') : $object->getNickname()))) ?>' type='text' class='form-control' id='nickname' name='nickname' required />
</div>
  
<div class='form-group'>
  <label for='wechat_id'>wechat_id</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['wechat_id']) ? strip_tags($_POST['wechat_id']) : '') : $object->getWechatId()))) ?>' type='text' class='form-control' id='wechat_id' name='wechat_id' required />
</div>
  
<div class='form-group'>
  <label for='openid'>openid</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['openid']) ? strip_tags($_POST['openid']) : '') : $object->getOpenid()))) ?>' type='text' class='form-control' id='openid' name='openid' required />
</div>
  
<div class='form-group'>
  <label for='introduction'>introduction</label>
  <textarea class='form-control' rows='5' id='introduction' name='introduction'><?php echo ($object->isNew() ? (isset($_POST['introduction']) ? htmlentities($_POST['introduction']) : '') : htmlentities($object->getIntroduction())) ?></textarea>
</div>
  
<div class='form-group'>
  <label for='cirtification'>cirtification</label>
  <textarea class='form-control' rows='5' id='cirtification' name='cirtification'><?php echo ($object->isNew() ? (isset($_POST['cirtification']) ? htmlentities($_POST['cirtification']) : '') : htmlentities($object->getCirtification())) ?></textarea>
</div>
  
<div class='form-group'>
  <label for='qr_code'>qr_code</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['qr_code']) ? strip_tags($_POST['qr_code']) : '') : $object->getQrCode()))) ?>' type='text' class='form-control' id='qr_code' name='qr_code' required />
</div>
  
<div class='form-group'>
  <label for='logo'>logo</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['logo']) ? strip_tags($_POST['logo']) : '') : $object->getLogo()))) ?>' type='text' class='form-control' id='logo' name='logo' required />
</div>
  
<div class='checkbox'>
  <label>
    <input type='checkbox' <?php echo ($object->isNew() ? (isset($_POST['active']) ? ($_POST['active'] ? 'checked="checked"' : '') : '') : ($object->getActive() ? "checked='checked'" : "")) ?> id='active' name='active' value='1' /> active
  </label>
</div>
  
<div class='form-group'>
  <label for='last_updated'>last_updated</label>
  <input value='<?php echo htmlentities(str_replace('\'', '"', ($object->isNew() ? (isset($_POST['last_updated']) ? strip_tags($_POST['last_updated']) : '') : $object->getLastUpdated()))) ?>' type='text' class='form-control' id='last_updated' name='last_updated' />
</div>

  <input type="submit" name="submit" value="<?php i18n_echo(array(
      'en' => 'Create', 
      'zh' => '创建'
  )) ?>" class="btn btn-default">
</form>
          
        </div>
      </div>
    </div>
  </div>
</div>

