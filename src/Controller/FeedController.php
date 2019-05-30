<?php

namespace App\Controller;
use App\Entity\Staff;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class FeedController extends AbstractController
{
    /**
     * @Route("/feed", name="feed")
     */
    public function index(Request $request, ObjectManager $manager)
    {
        // recuperer les articles
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->classByDate();


        //formulaire pour creer le post
        $post = new Post();
        $form =$this->createFormBuilder($post)
                    ->add('content', TextareaType::class, [
                        'attr'=>[
                            'class'=>"form-control",
                            'placeholder'=>"Saisissez votre commentaire ..."
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
            'formPost'=>$form->createView(),
            'posts'=>$posts
            
        ]);
    }

    

    

    /**
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('feed/home.html.twig', [
            'title'=>"Bienvenue sur le feed"
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
}
