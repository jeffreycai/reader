<?php
class Form {
  
  /**
   * print js to gerenate spam token
   * 
   * @param type $jquery_selector
   */
  public static function loadSpamToken($jquery_selector, $unique_id) {
    echo '
<script type="text/javascript">
jQuery(function($){
  $("'.$jquery_selector.' input[type=submit]").addClass("disabled");
  $.get("/' . get_sub_root() . 'form/spam/token/fetch?unique_id='.$unique_id.'", function(data) {
    var input = $("<input type=\'hidden\' name=\'"+data["key"]+"\' value=\'"+data["value"]+"\' />");
    $("'.$jquery_selector.'").append(input);
    $("'.$jquery_selector.' input[type=submit]").removeClass("disabled");
  }, "json");
});
</script>
';
  }
  
  /**
   * Generate a spam token and store in session
   */
  static function generateSpamToken($unique_id) {
    $token_key = get_random_string(8);
    $token_value = get_random_string(12);
    if (!isset($_SESSION['spam_tokens'])) {
      $_SESSION['spam_tokens'] = array();
    }
    // store the generated token in session
    $_SESSION['spam_tokens'][$unique_id] = array();
    $_SESSION['spam_tokens'][$unique_id]['key'] = $token_key;
    $_SESSION['spam_tokens'][$unique_id]['value'] = $token_value;
    
    return array('key' => $token_key, 'value' => $token_value);
  }
  
  static function checkSpamToken($unique_id) {
    $origin_token = isset($_SESSION['spam_tokens']) && isset($_SESSION['spam_tokens'][$unique_id]) ? $_SESSION['spam_tokens'][$unique_id] : null;
    if ($key = isset($origin_token['key']) ? $origin_token['key'] : false) {
      if (isset($_POST[$key]) && $_POST[$key] == $origin_token['value']) {
        return true;
      }
    }

    return false;
  }

}