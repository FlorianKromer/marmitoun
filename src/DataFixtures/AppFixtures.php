<?php
namespace App\DataFixtures;

use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 recettes! Bam!
        for ($i = 0; $i < 20; $i++) {
            $recette = new Recette();
            $recette->setTitre('recette '.$i);
            $recette->setDescription("lorem ipsum");
            $recette->setDateCreation(new \DateTime);
            $manager->persist($recette);
        }

        $manager->flush();
    }
}