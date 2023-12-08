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
        $Category1->setContent("rouge");
        $Category1->setImagesrc("/fruit.jpg");
        $manager->persist($Category1);
    
        $Category2 = new Category();
        $Category2->setName("LEGUMES");
        $Category2->setContent("vert");
        $Category2->setImagesrc("/legume.jpg");
        $manager->persist($Category2);
    
        $Category3 = new Category();
        $Category3->setName("EPICERIE");
        $Category3->setContent("parfum");
        $Category3->setImagesrc("/epicerie.jpg");
        $manager->persist($Category3);

        // PRODUCTS
        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setName($faker->unique()->words(4, true))
                ->setDescription($faker->realText(10));

            // Assign a random category to the product
            $randomCategory = $faker->randomElement([$Category1, $Category2, $Category3]);
            $product->setCategory($randomCategory);
        
            // dd($product);
            $manager->persist($product); 
        
        }


        

        $manager->flush();
    }
}
