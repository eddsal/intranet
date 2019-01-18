<?php

namespace App\Service;

use App\Entity\Grade;
use Doctrine\ORM\EntityManagerInterface;


class GradeManager
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveGrade(Grade $grade)
    {
        $em = $this->em;
        $em->persist($grade);
        $em->flush();
    }

}