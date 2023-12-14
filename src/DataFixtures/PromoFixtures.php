<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Promo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class PromoFixtures extends Fixture

{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $promos = [];

        for ($i=1; $i <= 6; $i++)
        { 
            $promo = new Promo();
            $promo->setTitle($faker->word())
                ->setDescription($faker->realText(10))
                ->setPrix(mt_rand(1, 500));
       
            $promos[] = $promo;

            $manager->persist($promo);
        }

        $manager->flush();
    }
}