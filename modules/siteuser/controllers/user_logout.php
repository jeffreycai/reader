<?php
$user = SiteUser::getCurrentUser();
$user->logout();

HTML::forward('');