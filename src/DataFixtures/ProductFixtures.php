<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // CATEGORIES
        $Category1 = new Category();
        $Category1->setName("FRUITS");
        $Category1->setContent("Explorez notre gamme de fruits frais et juteux. Nous offrons une sélection variée, des classiques intemporels aux exotiques. Chaque fruit est choisi pour sa fraîcheur et sa saveur.");
        $Category1->setImagesrc("/fruit.jpg");
        $manager->persist($Category1);
    
        $Category2 = new Category();
        $Category2->setName("LEGUMES");
        $Category2->setContent("Découvrez notre collection de légumes frais, essentiels pour une alimentation saine. Nous proposons des variétés classiques et rares, toutes sélectionnées pour leur fraîcheur et leur qualité.");
        $Category2->setImagesrc("/legume.jpg");
        $manager->persist($Category2);
    
        $Category3 = new Category();
        $Category3->setName("EPICERIE");
        $Category3->setContent("Parcourez notre épicerie pour des produits de qualité. Notre sélection inclut des articles essentiels, des produits biologiques aux spécialités artisanales, tous choisis pour leur authenticité.");
        $Category3->setImagesrc("/epicerie.jpg");
        $manager->persist($Category3);

        // PRODUCTS
        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setName($faker->unique()->words(4, true))
                ->setDescription($faker->realText(10));

            $randomCategory = $faker->randomElement([$Category1, $Category2, $Category3]);
            $product->setCategory($randomCategory);
        
            $manager->persist($product); 
        
        }


        

        $manager->flush();
    }
}
