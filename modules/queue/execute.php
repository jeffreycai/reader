<?php
require_once __DIR__ . '/../../bootstrap.php';

$thread_num = 150;

Queue::killAllDeadThreads(60);
for ($i = 0; $i < $thread_num; $i++) {
  Queue::fetchAndProceed();
}
