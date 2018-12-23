<?php
namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use \Bezhanov\Faker\ProviderCollectionHelper;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);

        // create 20 recettes! Bam!
        for ($i = 0; $i < 20; $i++) {
            $recette = new Recette();
            $recette->setTitre($faker->ingredient. ' with '. $faker->spice );
            $recette->setDescription($faker->text);
            // $recette->setDateCreation($faker->dateTimeThisMonth());

            for ($j=0; $j < mt_rand(0,3); $j++) { 
                $avis = new Avis;
                $avis->setPseudo($faker->name);
                $avis->setContenu($faker->realtext);
                $avis->setEmail($faker->email);
                $avis->setRecette($recette);
                $recette->getAvis()[] = $avis;
                $manager->persist($avis);
            }
            

            $manager->persist($recette);
        }


        $manager->flush();
    }
}