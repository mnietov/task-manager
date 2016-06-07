<?php

namespace My\TaskBundle\Event;

use My\TaskBundle\Entity\Task;
use Symfony\Component\EventDispatcher\Event;
use Monolog\Logger;

class TasksListener{
    
    private $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function onTaskCreate(Event $event){
        $name = $event->getTask()->getName();
        $this->logger->info('Task "' . $name . '" created!');
    }
    
    public function onTaskEdit(Event $event){
        $name = $event->getTask()->getName();
        $this->logger->info('Task "' . $name . '" edited!');
    }
    
    public function onTaskDelete(Event $event){
        $name = $event->getTask()->getName();
        $this->logger->info('Task "' . $name . '" deleted!');
    }
    
}