<?php

namespace My\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use My\UserBundle\Entity\User;
use My\UserBundle\Form\Type\UserType;

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
        if ($this->getUser()->getRoles()[0] === "ROLE_ADMIN"){
            $admin = true;
        }
        
        return $this->render('TaskBundle:Default:index.html.twig', array('tasks' => $tasks, 'lasttasks' => $this->getLastTasks(), 'admin' => $admin));
    }
    
    private function getLastTasks(){
        return $this->get('my_tasks.last_tasks')->getLastTasks();
    }
    
    public function registerAction(Request $request){
//        return $this->render('MyUserBundle:Default:register.html.twig', array());
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('login'));
        }
        
        return $this->render(
            "MyUserBundle:Default:register.html.twig",
            array(
                'form' => $form->createView()
            )
        );
    }
    
    /**
     * @Route("/{_locale}/", name="manage_users")
     */
    public function manageAction(){
        $repository = $this->getDoctrine()->getRepository('MyUserBundle:User');
        $users = $repository->findAll();
        
        return $this->render("MyUserBundle:Default:manage.html.twig", array('users' => $users));
    }
    
    public function invalidateAction($id){
        $repository = $this->getDoctrine()->getRepository('MyUserBundle:User');
        $user = $repository->find($id);
        $user->setRole("");
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        
        $users = $repository->findAll();
        
        return $this->render("MyUserBundle:Default:manage.html.twig", array('users' => $users));
        
    }
    
    public function validateAction($id){
        $repository = $this->getDoctrine()->getRepository('MyUserBundle:User');
        $user = $repository->find($id);
        $user->setRole("ROLE_USER");
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        
        $users = $repository->findAll();
        
        return $this->render("MyUserBundle:Default:manage.html.twig", array('users' => $users));
    }
   
}
