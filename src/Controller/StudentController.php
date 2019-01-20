<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SubjectRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="student")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'mySubjects' => $this->getUser()->getSubject(),
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
            //TODO
        }
        return $this->redirectToRoute('student');
    }
}
