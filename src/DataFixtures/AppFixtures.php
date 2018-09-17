<?php
namespace App\DataFixtures;

use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create();

        // create 20 recettes! Bam!
        for ($i = 0; $i < 20; $i++) {
            $recette = new Recette();
            $recette->setTitre('recette '.$i);
            $recette->setDescription($faker->text);
            // $recette->setDateCreation($faker->dateTimeThisMonth());
            $manager->persist($recette);
        }

        $manager->flush();
    }
}