<?php
require_once 'FormWidget.class.php';

class FormWidgetImage extends FormWidget {
  private $name;
  private $required;
  private $multiple;
  private $dimension;
  private $watermark;
  
  public function __construct($name, $conf) {
    parent::__construct($conf);
    $this->name = $name;
    $this->required = isset($conf['required']) ? $conf['required'] : 0;
    $this->multiple = isset($conf['multiple']) ? $conf['multiple'] : 0;
    if (isset($conf['dimension']) && preg_match('/^\d+x\d$/', $conf['dimension'])) {
      $tokens = explode('x', $conf['dimension']);
      $this->dimension = array($tokens[0], $tokens[1]);
    } else {
      $this->dimension = 0;
    }
    $this->watermark = isset($conf['watermark']) ? $conf['watermark'] : 0;
  }
  
  public function render($module, $model) {
    $rtn = "";
    $rtn .=
"\n<div class='form-group' id='$this->name'>
  <label>$this->name</label>
  <textarea name='$this->name' style='display: none;'></textarea>
  <div class='file-fields'" . ($this->multiple ? " style='border: 1px solid #999; padding: 6px;'" : "") . "></div>
" . ($this->multiple ? "  <button style='margin-top:6px;' class='add btn btn-primary btn-sm' type='button'>[[[ echo i18n(array('en' => 'Add image', 'zh' => '添加图片')) ]]]</button>" : "") . "
</div>
";
    $rtn .= "
[[[
  // get json string of prepopulated image links
  \$prepopulate = \$object->isNew() ? '' : \$object->get".format_as_class_name($this->name)."();
  if (\$prepopulate != '') {
    \$tokens = explode(\"\\n\", trim(\$prepopulate));
    \$prepopulate = array();
    foreach (\$tokens as \$token) {
      \$prepopulate[] = trim(\$token, \"\\n\\r\");
    }
  }
]]]
";
    // include jquery-ui, when we've got multiple images
    if ($this->multiple) {
      if (!Asset::checkAssetAdded('jquery-ui', 'js', 'backend')) {
        $js = "\n<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js'></script>\n";
        Asset::addDynamicAsset('jquery-ui', 'js', 'backend', $js);
        $rtn .= $js;
      }
    }

    // js
    $rtn .= "\n<script>
  $(function(){
    var container = $('#$this->name');
" . ($this->multiple ? "
    $('.file-fields', container).sortable({
      update: function(event, ui) {updateHiddenTextarea(container);}
    });
" : ""). "
    // initial value to pop
    var initial_images = [[[ echo \$prepopulate == '' ? '\"\"' : json_encode(\$prepopulate); ]]];
    if (initial_images != '') {
      for (var i in initial_images) {
        img = initial_images[i];
        var html = addImageRow(img, false);
        $('.file-fields', container).append(html);
      }
    } else {
      var html = addImageRow(false, true);
      $('.file-fields', container).append(html);
    }

    updateHiddenTextarea(container);
    // action when click select file button
    $(document).on('click', '#$this->name .select', function(){
      var tr = $(this).parents('.file-field');
      $('input[type=file]', tr).click();
      $('.upload', tr).prop('disabled', false);
    });
    // action when file filed is changed (we do validation here)
    $(document).on('change', '#$this->name input[type=file]', function(){
      var tr = $(this).parents('.file-field');
      var file = this.files[0];
      if (!file.type.match(/^image/)) {
        alert('[[[ echo i18n(array('en' => 'Upload file needs to be an image file', 'zh' => '上传文件需为图片文件')) ]]]');
      } else if (file.size > (1 * 1000 * 1000)) {
        alert('[[[ echo i18n(array('en' => 'File size should be less than', 'zh' => '文件大小应小于')) . ' 2MB' ]]]');
      } else {
        var reader = new FileReader();
        reader.onload = (function(e){
          $('.preview', tr).html('<img src=\"'+e.target.result+'\" style=\"height:150px;\" />');
        });
        reader.readAsDataURL(this.files[0]);
      }
    });
    // action when adding an new image row
    $(document).on('click', '#$this->name .add', function(){
      var html = addImageRow(false, true);
      $('.file-fields', container).append(html);
    });
    // action when uploading image via ajax
    $(document).on('click', '#$this->name .upload', function(){
      var tr = $(this).parents('.file-field');
      var file_field = $('input[type=file]', tr);
      var file = file_field[0].files[0];

      var formData = new FormData();
      formData.append('file', file, file.name);
      $('.btn', tr).prop('disabled', true);
      $('.upload i', tr).removeClass('fa-upload').addClass('fa-spin').addClass('fa-spinner');
      $.ajax({
        url: '[[[ echo uri(\"modules/$module/controllers/backend/".$model."_form_field_$this->name.php\" ,false) ]]]',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR) {
          if (typeof(data.error) !== 'undefined') {
            alert('[[[ echo i18n(array('en' => 'Error: ', 'zh' => '错误: ')) ]]]' + data.error);
          } else {
            tr.html(addImageRow(data.uri, false));;
            $('.remove',tr).data('uri', data.uri);
            updateHiddenTextarea(container);
          }
          $('.btn', tr).prop('disabled', false);
          $('.upload i', tr).removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-upload');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('[[[ echo i18n(array('en' => 'ajax error: ', 'zh' => 'ajax失败')) ]]]: ' + textStatus);
          $('.btn', tr).prop('disabled', false);
          $('.upload i', tr).removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-upload');
        }
      });
    });
    // action when removing an image
    $(document).on('click', '#$this->name .remove', function(){
      var tr = $(this).parents('.file-field');
      if (typeof($(this).data('uri')) !== 'undefined') {
        var img = $(this).data('uri');
        $('.btn', tr).prop('disabled', true);
        $('.remove i', tr).addClass('fa-spin').addClass('fa-spinner').removeClass('fa-remove');
        // ajax to remove the image
        $.ajax({
          url: '[[[ echo uri(\"modules/$module/controllers/backend/".$model."_form_field_$this->name\".\"_remove.php\" ,false) ]]]?path=' + encodeURIComponent(img),
          type: 'POST',
          dataType: 'json',
          success: function(data, textStatus, jqXHR) {
            if (typeof(data.error) !== 'undefined') {
              alert('[[[ echo i18n(array('en' => 'Error: ', 'zh' => '错误: ')) ]]]' + data.error);
".(!$this->multiple ? "tr.html(addImageRow(false, true));" : "tr.fadeOut(function(){tr.remove();});")."
              updateHiddenTextarea(container);
            } else {
".(!$this->multiple ? "tr.html(addImageRow(false, true));" : "tr.fadeOut(function(){tr.remove();});")."
              updateHiddenTextarea(container);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert('[[[ echo i18n(array('en' => 'ajax error: ', 'zh' => 'ajax失败')) ]]]: ' + textStatus);
            $('.btn', tr).prop('disabled', false);
            $('.remove i', tr).removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-remove');
            updateHiddenTextarea(container);  
          }
        });
      } else {
".($this->multiple ? "tr.fadeOut();" : "")."
      }
    });

    // functions
    function addImageRow(img, isnew) {
      var img_html = img ? '<img src=\"/[[[ echo get_sub_root() ]]]'+img+'\" style=\"height:150px;\" />' : '<div style=\"height:150px;width:200px;background-color:#AAA;\"></div>';
      var upload_button = isnew ? 
        '<button type=\"button\" class=\"btn btn-default btn-sm upload\" disabled><i class=\"fa fa-upload\"></i></button>' :
        '<!-- <button type=\"button\" class=\"btn btn-default btn-sm upload\" disabled><i class=\"fa fa-upload\"></i></button> -->';
      var select_button = isnew ?
        '<button type=\"button\" class=\"btn btn-default btn-sm select\"><i class=\"fa fa-file\"></i></button>' :
        '<!-- <button type=\"button\" class=\"btn btn-default btn-sm select\"><i class=\"fa fa-file\"></i></button> -->';
      var data_uri = isnew ? '' : 'data-uri=\"'+img+'\"';
      return ('\\n\\
    <div class=\"file-field\" style=\"margin-bottom:6px; position:relative;\">\\n\\
      <div class=\"preview\">'+img_html+'</div>\\n\\
      <div class=\"btn-group\" style=\"position:absolute; bottom:5px; left:5px; \" aria-label=\"...\">\\n\\
        <input type=\"file\" class=\"btn btn-default btn-sm\" style=\"display:none;\" />\\n\\
        '+select_button+'\\n\\
        '+upload_button+'\\n\\
        <button type=\"button\" class=\"btn btn-default btn-sm remove\" '+data_uri+'><i class=\"fa fa-remove\"></i></button>\\n\\
      </div>\\n\\
    </div>');
    }

    function updateHiddenTextarea(container) {
      var html = '';
      $('.preview img', container).each(function(){
        var uri = $(this).attr('src');
        // remove subroot
        var subroot = '[[[ echo get_sub_root() ]]]';
        uri = uri.substr(subroot.length+1, uri.length-1);
        html = html + uri + " . '"\n"' . ";
      });
      $('textarea', container).val(html);
    }
  });
</script>
";

    return $rtn;
  }
  
  public function validate() {
    $rtn = "\n  // validation for $".$this->name."\n";
    $rtn .= '  $'.$this->name.' = isset($_POST["'.$this->name.'"]) ? strip_tags(trim($_POST["'.$this->name.'"])) : null;';
    if ($this->required) {
      $rtn .= '
  if (empty($'.$this->name.')) {
    Message::register(new Message(Message::DANGER, i18n(array("en" => "'.$this->name.' is required.", "zh" => "请填写'.$this->name.'"))));
    $error_flag = true;
  }
';
    }
    return $rtn;
  }
  
  public function proceed() {
    $rtn = "\n  // proceed for $".$this->name."\n";
    $rtn .= '  $object->set'.format_as_class_name($this->name).'($'.$this->name.');
';
    return $rtn;
  }
}