<?php

namespace My\TaskBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;

class LastTasks {
    
    private $repository;


    public function __construct(ObjectManager $om) {
        $this->repository =$om->getRepository('TaskBundle:Task');
    }
    
    public function getLastTasks(){
        return $this->repository->findBy(array(), array('createdAt' => 'DESC'), 3);
    }
    
    
}