<?php

namespace My\TaskBundle\Utility;


class CalculeTasks {
   
    public function calculeTasksUser($tasks, $users) {
        return $tasks * $users;
    }
    
}
