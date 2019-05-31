<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\Comment;
use Faker;


class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // initialisation de l'objet Faker
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i < 10; $i++)
        {
            $post = new Post();
            
            $post->setContent($faker->paragraph);
            $post->setLikes(0);
            $post->setCreatedAt(new \DateTime());
            
            
            $manager->persist($post);
     

        for ($k = 1; $k <= mt_rand(2, 4); $k++) {
            $comment = new Comment();
            $now = new \DateTime();
            $intervalDate = $now->diff($post->getCreatedAt());
            $days = $intervalDate->days;
            $minimum = '-' . $days . ' days';


        

            $comment->setContent($faker->paragraph);
            $comment->setCreatedAt($faker->dateTimeBetween($minimum));
            $comment->setArticle($post);


            $manager->persist($comment);



        }
        $manager->flush();

}
}

}

