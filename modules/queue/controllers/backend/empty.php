<?php
$type = isset($_POST['type']) && !empty($_POST['type']) ? strip_tags(trim($_POST['type'])) : null;


if (Queue::emptyQueue($type)) {
  Message::register(new Message(Message::SUCCESS, 'Queue ' . $type . ' emptied'));
} else {
  Message::register(new Message(Message::DANGER, 'Queue ' . $type . ' failed to be emptied'));
}


HTML::forwardBackToReferer();