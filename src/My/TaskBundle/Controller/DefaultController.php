<?php

namespace My\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use My\TaskBundle\Entity\Task;
use My\TaskBundle\Form\Type\TaskType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $tasks = $repository->findAll();
        
        return $this->render('TaskBundle:Default:index.html.twig', array('tasks' => $tasks, 'lasttasks' => $this->getLastTasks()));
    } 
    
    private function getLastTasks(){
        return $this->get('my_tasks.last_tasks')->getLastTasks();
    }
    
    public function createAction(Request $request){
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);
        
        if ($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($task);
            $em->flush();
            return $this->redirect($this->generateUrl('task_homepage'));
        }
        
        return $this->render(
            "TaskBundle:Default:create.html.twig",
            array(
                'form' => $form->createView()
            )
        );
    }
    
    public function editAction($id, Request $request){
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $task = $repository->find($id);
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);
        
        if ($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($task);
            $em->flush();
            return $this->redirect($this->generateUrl('task_homepage'));
        }
        
        return $this->render(
            "TaskBundle:Default:edit.html.twig",
            array(
                'form' => $form->createView()
            )
        );
        
    }
    
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $task = $repository->find($id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($task);
        $em->flush();
        return $this->redirect($this->generateUrl('task_homepage'));
    }
}
