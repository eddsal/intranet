<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;


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

    public function getAverage(User $user, Integer $precision = null)
    {
        if (!$precision) {
            $precision = 2;
        }
        $grades = $user->getGrades();
        if (sizeof($grades) === 0) {
            return null;
        }
        $average = 0;
        foreach ($grades as $grade) {
            $average += $grade->getGrade();
        }
        return round($average / sizeof($grades), $precision);
    }

}