<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Staff;
use App\Entity\Comment;
use App\Form\InscriptionType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FeedController extends Controller
{
    /**
     * @Route("/feed", name="feed")
     */
    public function index(PostRepository $repo, Request $request, ObjectManager $manager)
    {
        // recuperer les articles
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->classByDate();

       


        //formulaire pour creer le post
        $post = new Post();
        $form =$this->createFormBuilder($post)
                    ->add('content', TextareaType::class, [
                        'label'=>'Saisissez votre commentaires',
                        'attr'=>[
                            'class'=>"form-control",
                            'placeholder'=>"Saisissez votre commentaire ...",
                            
                        ]
                    ])
                    ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
            {
                $post->setCreatedAt(new \DateTime());
                $manager->persist($post);
                $manager->flush();

                return $this->redirectToRoute('feed');
            }

           
        
        
        return $this->render('feed/index.html.twig', [
            'controller_name'=>'FeedController',
            'formPost'=>$form->createView(),
            'posts'=>$posts
            
            
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
    public function staff() {
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
}
