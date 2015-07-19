<?php

Log::truncate();

Message::register(new Message(Message::SUCCESS, 'Log emptied'));
HTML::forward('admin/log/list');