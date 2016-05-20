<?php

namespace My\TaskBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use My\TaskBundle\Entity\Task;

class LoadTaskData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $task = new Task();
        $task->setName('Test task');
        $task->setDescription('This is a test task to test data fixtures');

        $manager->persist($task);
        $manager->flush();
    }
}