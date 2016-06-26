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
    /**
     * @Route("/{_locale}/", name="task_homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $tasks = $repository->findAll();
                        
        $admin = false;
        if ($this->getUser() === null) {
            $authenticationUtils = $this->get('security.authentication_utils');

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render(
                            'TaskBundle:Security:login.html.twig', array(
                            // last username entered by the user
                            'last_username' => $lastUsername,
                            'error' => $error,
                                )
            );
        }
        if ($this->getUser()->getRoles()[0] === "ROLE_ADMIN"){
            $admin = true;
        }
        
        return $this->render('TaskBundle:Default:index.html.twig', array('tasks' => $tasks, 'lasttasks' => $this->getLastTasks(), 'admin' => $admin));
    } 
    
    private function getLastTasks(){
        return $this->get('my_tasks.last_tasks')->getLastTasks();
    }
    
    /**
     * @Route("/{_locale}/", name="create-task")
     */
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
    
    /**
     * @Route("/{_locale}/", name="edit-task")
     */
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
