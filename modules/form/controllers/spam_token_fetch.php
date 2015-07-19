<?php

// check the request
if (!isset($_GET['unique_id'])) {
  exit;
}
$tokens = Form::generateSpamToken($_GET['unique_id']);

HTML::sendJSONresponse(json_encode($tokens));