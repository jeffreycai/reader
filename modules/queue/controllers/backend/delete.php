<?php
$qid = isset($vars[1]) ? $vars[1] : null;
$queue = Queue::findById($qid);

if (!$queue) {
  Message::register(new Message(Message::DANGER, i18n(array(
      'en' => 'Queue record does not exist',
      'zh' => '队列项不存在'
  ))));
} else {
  if ($queue->delete()) {
    Message::register(new Message(Message::SUCCESS, i18n(array(
        'en' => 'Queue item deleted successfully',
        'zh' => '队列项删除成功'
    ))));
  } else {
    Message::register(new Message(Message::DANGER, i18n(array(
        'en' => 'Queue item deleted failed',
        'zh' => '队列项删除失败'
    ))));
  }
}

HTML::forwardBackToReferer();