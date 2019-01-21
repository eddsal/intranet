<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class UserManager
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveUser(User $user)
    {
        if (empty($user->getDate())) {
            $user->setDate(new \DateTime());
        }
        $em = $this->em;
        $em->persist($user);
        $em->flush();
    }

}