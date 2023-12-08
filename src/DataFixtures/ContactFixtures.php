<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ContactFixtures extends Fixture

{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        
        $contacts = [];

        for ($i=1; $i <= 20; $i++)
        { 
            $contact = new Contact();
            $contact->setFirstname($faker->name())
                ->setEmail($faker->email())
                ->setSubject('Demande n°' . ($i))
                ->setMessage($faker->text());
       
            $contacts[] = $contact;

            $manager->persist($contact);
        }

        $manager->flush();
    }
}