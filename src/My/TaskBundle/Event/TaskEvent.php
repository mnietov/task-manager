<?php

namespace My\TaskBundle\Event;

use My\TaskBundle\Entity\Task;
use Symfony\Component\EventDispatcher\Event;

class TaskEvent extends Event{
    
    private $task;
    
    public function __construct($task) {
        $this->task = $task;
    }
    
    public function getTask(){
        return $this->task;
    }
    
}
