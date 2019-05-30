<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;
use Faker;


class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // initialisation de l'objet Faker
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 10; $i++)
        {
            $post = new Post();
            
            $post->setContent($faker->paragraph);
            $post->setImage($faker->imageUrl($width = 240, $height = 180));
            $post->setCreatedAt(new \DateTime());
            
            $manager->persist($post);
        }
        $manager->flush();
    }
}
