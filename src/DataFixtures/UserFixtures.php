<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // USERS
        $users = [];

        $admin = new User();
        $admin->setEmail('admin@primeur.com')
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"])
            ->setFirstname('admin')
            ->setPlainPassword('admin');

        $users[] = $admin;
        $manager->persist($admin);


        for ($j = 1; $j <= 5; $j++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles(["ROLE_USER"]);
            $user->setFirstname($faker->firstName);
            $user->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
