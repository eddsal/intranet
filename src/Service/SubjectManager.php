<?php

namespace App\Service;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;


class SubjectManager
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveSubject(Subject $subject)
    {
        $em = $this->em;
        $em->persist($subject);
        $em->flush();
    }


}