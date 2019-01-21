<?php

namespace App\Service;

use App\Entity\Grade;
use App\Entity\Subject;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;


class GradeManager
{
    private $em;
    private $user;

    function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function saveGrade(Grade $grade)
    {
        $em = $this->em;
        $em->persist($grade);
        $em->flush();
    }

    public function checkRight(User $student, Subject $subject): bool
    {
        $hasRight = true;
        $teacher = $this->user;
        if (empty($subject) || empty($student)) {
            $hasRight = false;
        }
        if ($subject->getTeacher()->getId() !== $teacher->getId()) {
            $hasRight = false;
        }
        if (!in_array($student, $subject->getRegistered()->getValues())) {
            $hasRight = false;
        }
        return $hasRight;
    }

}