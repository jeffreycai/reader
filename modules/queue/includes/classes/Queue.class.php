<?php
require_once "BaseQueue.class.php";

class Queue extends BaseQueue {
  
  const STATUS_QUEUED = 0;
  const STATUS_INPROGRESS = 1;
  const STATUS_ABORTED = 2;
  const STATUS_SUCCESS = 3;
  const STATUS_FAIL = 4;
  
  const PRIORITY_LOWEST = 0;
  const PRIORITY_LOW = 10;
  const PRIORITY_MEDIUM = 20;
  const PRIORITY_HIGH = 30;
  const PRIORITY_HIGHEST = 40;
  
  static function emptyQueue($type = null) {
    if (is_null($type)) {
      return self::truncate();
    } else {
      global $mysqli;
      $query = "DELETE FROM queue WHERE type='" . $type . "'";
      return $mysqli->query($query);
    }
  }
  
  /**
   * 
   * @param string $type
   * @param string $description
   * @param string $function
   * @param array $args
   * @param int $priority
   * @return type
   */
  static function addToQueque($type, $description, $function, $args, $priority = 0) {
    $queue = new Queue();
    $queue->setCreatedAt(time());
    $queue->setStatus(self::STATUS_QUEUED);
    $queue->setArguments(serialize($args));
    $queue->setFunction($function);
    $queue->setType($type);
    $queue->setDescription($description);
    $queue->setPriority($priority);
    return $queue->save();
  }
  
  static function killAllDeadThreads($expire_time = 60, $type = null) {
    global $mysqli;
    $type_condition = is_null($type) ? '' : " AND type='" . $type . "'";
    $query = "UPDATE queue SET status=" . self::STATUS_ABORTED . ", status_info='Timeout threshold of $expire_time secs reached', finished_at=".time()." WHERE status=" . self::STATUS_INPROGRESS . " AND started_at < " . (time() - $expire_time) . $type_condition;
    return $mysqli->query($query);
  }
  
  static function fetchAndProceed($type = null) {
    global $mysqli;
    $type_condition = is_null($type) ? '' : " AND type='" . $type . "'";
    $query = "SELECT * FROM queue WHERE status=" . self::STATUS_QUEUED . $type_condition . " ORDER BY priority DESC, created_at ASC LIMIT 1";
    $result = $mysqli->query($query);
    if ($result && $obj = $result->fetch_object()) {
      // fetch the thread, flag it as in progess
      $thread = new Queue();
      DBObject::importQueryResultToDbObject($obj, $thread);
      $thread->setStatus(self::STATUS_INPROGRESS);
      $thread->setStartedAt(time());
      $thread->save();
      
      // do the job
      try {
        $function = $thread->getFunction();
        $args = unserialize($thread->getArguments());
        
        if (function_exists($function)) {
          $result = $function($args);
          if ($result) {
            $thread->setStatus(self::STATUS_SUCCESS);
            $thread->setFinishedAt(time());
            $thread->save();
            return true;
          } else {
            $thread->setStatus(self::STATUS_FAIL);
            $thread->setFinishedAt(time());
            $thread->save();
            throw new Exception("Thread function returns false");
          }
        } else {
          throw new Exception("Function $function does not exist");
        }
      } catch (Exception $e) {
        $thread->setStatus(self::STATUS_FAIL);
        $thread->setFinishedAt(time());
        $thread->setStatusInfo($e->getMessage());
        $thread->save();
      }
    }
    return false;
  }
  
  static function fetchAndProceedByQueueId($id) {
    global $mysqli;
    $query = "SELECT * FROM queue WHERE id=$id";
    $result = $mysqli->query($query);
    if ($result && $obj = $result->fetch_object()) {
      // fetch the thread, flag it as in progess
      $thread = new Queue();
      DBObject::importQueryResultToDbObject($obj, $thread);
      $thread->setStatus(self::STATUS_INPROGRESS);
      $thread->setStatusInfo(null);
      $thread->setStartedAt(time());
      $thread->save();
      
      // do the job
      try {
        $function = $thread->getFunction();
        $args = unserialize($thread->getArguments());
        
        if (function_exists($function)) {
          $result = $function($args);
          if ($result) {
            $thread->setStatus(self::STATUS_SUCCESS);
            $thread->setFinishedAt(time());
            $thread->save();
            return true;
          } else {
            $thread->setStatus(self::STATUS_FAIL);
            $thread->setFinishedAt(time());
            $thread->save();
            throw new Exception("Thread function returns false");
          }
        } else {
          throw new Exception("Function $function does not exist");
        }
      } catch (Exception $e) {
        $thread->setStatus(self::STATUS_FAIL);
        $thread->setFinishedAt(time());
        $thread->setStatusInfo($e->getMessage());
        $thread->save();
      }
    }
    return false;
  }
  
  static function findAllWithPage($page, $entries_per_page, $orderby = 'created_at', $order = 'DESC') {
    global $mysqli;
    $query = "SELECT * FROM queue ORDER BY $orderby $order LIMIT " . ($page - 1) * $entries_per_page . ", " . $entries_per_page;
    $result = $mysqli->query($query);
    
    $rtn = array();
    while ($result && $b = $result->fetch_object()) {
      $obj= new Queue();
      DBObject::importQueryResultToDbObject($b, $obj);
      $rtn[] = $obj;
    }
    
    return $rtn;
  }
  
  public function getPriorityLiteral() {
     $p = $this->getDbFieldPriority();
     switch ($p) {
       case self::PRIORITY_LOW:
         return 'Low';
       case self::PRIORITY_LOWEST:
         return 'Lowest';
       case self::PRIORITY_MEDIUM:
         return 'Medium';
       case self::PRIORITY_HIGH:
         return 'High';
       case self::PRIORITY_HIGHEST:
         return 'Highest';
       default:
         return $p;
     }
   }
}
