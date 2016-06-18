<?php

namespace My\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use My\TaskBundle\Entity\Task;
use My\TaskBundle\Form\Type\TaskType;
use My\TaskBundle\Event\TaskEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $tasks = $repository->findAll();
                        
        $admin = false;
        if ($this->getUser()->getRoles() === "ROLE_ADMIN"){
            $admin = true;
        }
        
        return $this->render('TaskBundle:Default:index.html.twig', array('tasks' => $tasks, 'lasttasks' => $this->getLastTasks(), 'admin' => $admin));
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
            $event = new TaskEvent($task); 
            $dispatcher = $this->get('event_dispatcher'); 
            $dispatcher->dispatch('task.create', $event);
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
            $event = new TaskEvent($task); 
            $dispatcher = $this->get('event_dispatcher'); 
            $dispatcher->dispatch('task.edit', $event);
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
        $event = new TaskEvent($task); 
        $dispatcher = $this->get('event_dispatcher'); 
        $dispatcher->dispatch('task.delete', $event);
        return $this->redirect($this->generateUrl('task_homepage'));
    }
    
    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
