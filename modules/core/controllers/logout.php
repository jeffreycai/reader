<?php
$user = User::getInstance();
$user->logout();

HTML::forwardBackToReferer();
