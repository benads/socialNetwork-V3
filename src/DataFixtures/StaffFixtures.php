<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Staff;
use Faker;

class StaffFixtures extends Fixture

{
    public function load(ObjectManager $em)
    {
        // initialisation de l'objet Faker
        $faker = Faker\Factory::create('fr_FR');

        // créations du staff

        for ($i=0; $i < 30; $i++) {


            $staff = new Staff();
            $staff->setName($faker->Name);
            $staff->setLastName($faker->lastName);
            $staff->setProfession($faker->jobTitle);
            $staff->setEmail($faker->email);

            $em->persist($staff);
        }



        $em->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
