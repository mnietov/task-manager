<?php

namespace My\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use My\TaskBundle\Entity\Task;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $tasks = $repository->findAll();
        
        return $this->render('TaskBundle:Default:index.html.twig', array('tasks' => $tasks));
    }
    
    public function showAddFormAction()
    {
        return $this->render('TaskBundle:Default:add.html.twig');
    }
    
    public function addAction(Request $request)
    {
        $task = new Task();
        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($task);
        $em->flush();
        return $this->redirect($this->generateUrl('task_homepage'));
    }
    
    public function showEditFormAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $task = $repository->find($id);
        return $this->render('TaskBundle:Default:edit.html.twig', array('task' => $task));
    }
    
    public function editAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $task = $repository->find($id);
        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($task);
        $em->flush();
        return $this->redirect($this->generateUrl('task_homepage'));
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
