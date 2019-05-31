<?php

namespace App\Controller;

use App\Entity\Post;

use App\Entity\User;
use App\Entity\Staff;
use App\Entity\Comment;
use App\Form\StaffType;
use App\Form\InscriptionType;
use App\Repository\PostRepository;
use App\Repository\StaffRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FeedController extends AbstractController
{
    /**
     * @Route("/feed", name="feed")
     */
    public function index(PostRepository $repo, Request $request, ObjectManager $manager, UserInterface $user)
    {
        $userId = $user->getUsername();
        // recuperer les articles
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->classByDate();

        $repos = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repos->findAll();

        //formulaire pour creer le post
        $post = new Post();
        $form =$this->createFormBuilder($post)
                    ->add('content', TextareaType::class, [
                        'label'=>'Saisissez votre article',
                        'attr'=>[
                            'class'=>"form-control",
                            'placeholder'=>"Saisissez votre article ...",   
                        ]  
                    ])
                    ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
            {
               
                $post->setCreatedAt(new \DateTime());
                $post->setLikes(0);
                
                $manager->persist($post);
                $manager->flush();

                return $this->redirectToRoute('feed');
            }

        return $this->render('feed/index.html.twig', [
            'controller_name'=>'FeedController',
            'formPost'=>$form->createView(),
            'user'=>$userId,
            'posts'=>$posts,
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/", name="home")
     */

    public function home(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $forma = $this->createForm(InscriptionType::class, $user);

        $forma->handleRequest($request);

        if($forma->isSubmitted() && $forma->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('connexion');
        }
        
        

        return $this->render('feed/home.html.twig', [
            'title'=>"Bienvenue sur le feed",
            'forma'=>$forma->createView()
          
        ]);
    }


    /**
     * @Route("/staff", name="staff")
     */
    public function staff() 
    {
        $repo = $this->getDoctrine()->getRepository(Staff::class);
        $staff = $repo->findAll();
        return $this->render('feed/staff.html.twig', [
            'staff'=>$staff
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function login() {
       
        return $this->render('feed/login.html.twig');
    }


    /**
     * @Route("/deconnexion", name="logout")
     */

     public function logout() {}

       /**
        * @var StaffRepository
        * @var ObjectManager
        */

        private $repository;

        private $manager;

       public function __construct(StaffRepository $repository, ObjectManager $manager)
       {
           $this->repository = $repository;
           $this->em = $manager;
       }



       /**
        * @Route("/admin", name="admin")
        */

        public function admin(Request $request, ObjectManager $manager) 
        {
            $staff = $this->repository->findAll();
            return $this->render('feed/admin.html.twig', [
                compact('staff'),
                'staffs' => $staff       
        ]);
        }

       /**
        * @Route("/admin/{id}", name="admin-edit")
        * @param Staff $staff
        * @return \Symfony\Component\HttpFoundation\Response   
        */

        public function adminEdit(Request $request, ObjectManager $manager, Staff $staff) 
        {
           
            $form = $this->createForm(StaffType::class, $staff);
 
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) 
            {
                $manager->persist($staff);
                $manager->flush();
                return $this->redirectToRoute('staff');
            }

            return $this->render('feed/adminEdit.html.twig', [
                 compact('staff'),
                'form'=> $form->createView(),
                'staffs'=>$staff
                
                
                
            ]);
        }

        /**
         * @Route("admin/create/new", name="staff-new")
         */

         public function newStaff(Request $request, ObjectManager $manager) 
         {
             $staff= new Staff();
             $form = $this->createForm(StaffType::class, $staff);
 
             $form->handleRequest($request);
             if($form->isSubmitted() && $form->isValid()) 
             {
                 $manager->persist($staff);
                 $manager->flush();
                 return $this->redirectToRoute('staff');
             }
             return $this->render('feed/create.html.twig', [
                compact('staff'),
               'form'=> $form->createView(),
               'staffs'=>$staff     
           ]);
         }


       /**
        * @Route("/admin", name="delete_staff", methods="DELETE")
        * @param Staff $staff  
        */

          public function deleteStaff(Staff $staff, Request $request)
          {
            
          
                 
                 $manager->remove($staff);
                 $manager->persist($staff);
                 $manager->flush();
                
              
             
             return $this->redirectToRoute('feed/admin.html.twig');
          }  

        

 
}
