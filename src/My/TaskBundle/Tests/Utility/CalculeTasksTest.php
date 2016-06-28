<?php

namespace My\TaskBundle\Tests\Utility;

use My\TaskBundle\Utility\CalculeTasks;


class CalculeTasksTest extends \PHPUnit_Framework_TestCase {
   
    public function calculeTasksUserTest() {
        $calc = new CalculeTasks();
        $result = $calc->calculeTasksUser(3, 40);
        
        $this->assertEquals(120, $result);
    }
    
}
