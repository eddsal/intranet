<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SubjectRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/student")
 * @IsGranted("ROLE_USER")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="student")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'mySubjects' => $this->getUser()->getSubjects(),
            'subjects' => $subjectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/register/{id}", name="student_register")
     */
    public function register(Request $request, SubjectRepository $subjectRepository, UserManager $userManager): Response
    {
        $subject = $subjectRepository->find($request->get('id'));
        if (!empty($subject)) {
            $user = $this->getUser()->addSubject($subject);
            $userManager->saveUser($user);
        }
        return $this->redirectToRoute('student');
    }

    /**
     * @Route("/grade", name="student_grade")
     */
    public function grade(Request $request, UserManager $userManager): Response
    {
        $user = $this->getUser();
        $grades = $user->getGrades();
        return $this->render('student/grade.html.twig', [
            'grades' => $grades,
            'average' => $userManager->getAverage($user),
        ]);
    }
}
