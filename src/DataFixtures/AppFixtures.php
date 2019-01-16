<?php

namespace App\DataFixtures;

use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Subject;

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
                'username' => 'user1',
                'role' => [],
                'password' => 'user42!'
            ],
            [
                'username' => 'user2',
                'role' => [],
                'password' => 'user42!'
            ],
            [
                'username' => 'user3',
                'role' => [],
                'password' => 'user42!'
            ],
            [
                'username' => 'prof1',
                'role' => ['ROLE_TEACHER'],
                'password' => 'prof42!'
            ],
            [
                'username' => 'prof2',
                'role' => ['ROLE_TEACHER'],
                'password' => 'prof42!'
            ],
            [
                'username' => 'admin1',
                'role' => ['ROLE_ADMIN'],
                'password' => 'admin42!'
            ]
        ];

        $subjects = ['PHP', 'JS', 'html/css', 'Golang', 'Python', 'android', 'swift'];

        $teachers = [''];

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
            if ($user['role'] === ['ROLE_TEACHER']) {
                $teachers[] = $person;
            }
        }

        $a = 0;
        foreach ($subjects as $subject) {
            $sub = new Subject();
            $sub->setName($subject);
            if ($teachers[$a] !== '') {
                $sub->setUser($teachers[$a]);
            }
            $a++;
            if ($a === sizeof($teachers)) {
                $a = 0;
            }
            $manager->persist($sub);
        }

        $manager->flush();
    }
}
