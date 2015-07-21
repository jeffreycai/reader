<?php
$qid = isset($vars[1]) ? $vars[1] : null;
$queue = Queue::findById($qid);

if (!$queue) {
  Message::register(new Message(Message::DANGER, i18n(array(
      'en' => 'Queue record does not exist',
      'zh' => '队列项不存在'
  ))));
} else {
  try {
    $function = $queue->getFunction();
    if (!function_exists($function)) {
      throw new Exception(i18n(array(
          'en' => "Function $function does not exist",
          'zh' => "函数$function不存在"
      )));
    }
    
    if (Queue::fetchAndProceedByQueueId($qid)) {
      Message::register(new Message(Message::SUCCESS, 'Queue item run successfully'));
    } else {
      Message::register(new Message(Message::DANGER, 'Queue item run failed'));
    }
    

  } catch (Exception $e) {
    Message::register(new Message(Message::DANGER, $e->getMessage()));
  }
}
HTML::forwardBackToReferer();