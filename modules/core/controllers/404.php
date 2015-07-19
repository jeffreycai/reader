<?php

/** views **/

header("HTTP/1.0 404 Not Found");

$html = new HTML();

$html->output('<h1>Page not found</h1>');
$html->output('<p>The page you are looking for does not exist.</p>');


