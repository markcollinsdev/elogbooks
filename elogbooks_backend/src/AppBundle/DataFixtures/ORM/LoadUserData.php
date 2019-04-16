<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadJobData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            ['name' => 'timmy tester1', 'email' => 'timmy.tester1@example.com'],
            ['name' => 'timmy tester2', 'email' => 'timmy.tester2@example.com'],
            ['name' => 'timmy tester3', 'email' => 'timmy.tester3@example.com'],
            ['name' => 'timmy tester4', 'email' => 'timmy.tester4@example.com'],
            ['name' => 'timmy tester5', 'email' => 'timmy.tester5@example.com'],
        ];

        foreach ($users as $user) {
            $newUser= new User();
            $newUser->setName($user['name']);
            $newUser->setEmail($user['email']);
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}