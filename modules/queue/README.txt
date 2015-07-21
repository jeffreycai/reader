<?php

//Queue::emptyQueue('test');
//Queue::addToQueque('test', 'A test to add to queue', 'helloworld', array('lower' => 'priority'));
//sleep(1);
//Queue::addToQueque('test', 'A test to add to queue', 'helloworld', array('higher' => 'priority'));

Queue::killAllDeadThreads(100, 'test');
Queue::fetchAndProceed('blog');

echo "<br />done";
//
function helloworld($args) {
  foreach ($args as $key => $val); {
    throw new Exception("Oh no.....");
sleep(10);
    echo "$key => $val";
    return true;
  }
}