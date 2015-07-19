<?php
$lang_to = $vars[1];

$url = preg_replace('/\/' .  get_language() . '/', '/'.$lang_to, $_SERVER['HTTP_REFERER'], 1);

HTML::forward($url);