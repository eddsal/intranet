<?php

namespace App\DataFixtures;

use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;
    private $userManager;

    function __construct(UserPasswordEncoderInterface $passwordEncoder, UserManager $userManager)
    {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            [
                'username' => 'user',
                'role' => [],
                'password' => 'user42!'
            ],
            [
                'username' => 'prof',
                'role' => ['ROLE_TEACHER'],
                'password' => 'prof42!'
            ],
            [
                'username' => 'admin',
                'role' => ['ROLE_ADMIN'],
                'password' => 'admin42!'
            ]
        ];

        foreach ($users as $user) {
            $person = new User();
            $person->setUsername($user['username']);
            $person->setRoles($user['role']);
            $person->setPassword(
                $this->passwordEncoder->encodePassword(
                    $person,
                    $user['password']
                )
            );
            $this->userManager->addUser($person);
        }

//        $manager->flush();
    }
}
