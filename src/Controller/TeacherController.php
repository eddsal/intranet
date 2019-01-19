<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\GradeTeacherType;
use App\Repository\GradeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Service\GradeManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", name="teacher")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('teacher/index.html.twig', [
            'subjects' => $user->getSubjects(),
        ]);
    }

    /**
     * @Route("/subject/{id}", name="teacher_subject")
     */
    public function subject(Request $request, SubjectRepository $subjectRepository): Response
    {
        $user = $this->getUser();
        $id = $request->get('id');
        $subject = $subjectRepository->find($id);
        if (empty($subject)) {
            return $this->redirectToRoute('teacher');
        }

        if ($subject->getUser()->getId() !== $user->getId()) {
            return $this->redirectToRoute('teacher');
        }

        return $this->render('teacher/subject.html.twig', [
            'subject' => $subject
        ]);
    }

    /**
     * @Route("/subject/{id}/new-grade/{user_id}", name="teacher_new_grade", methods={"GET","POST"})
     */
    public function newGrade(Request $request, GradeManager $gradeManager, SubjectRepository $subjectRepository, UserRepository $userRepository): Response
    {
        $subject = $subjectRepository->find($request->get('id'));
        $user = $userRepository->find($request->get('user_id'));

        if (!$gradeManager->checkRight($user, $subject)) {
            return $this->redirectToRoute('teacher_subject', ['id' => $subject->getId()]);
        }

        $grade = new Grade();
        $form = $this->createForm(GradeTeacherType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade->setUser($user)
                ->setSubject($subject);
            $gradeManager->saveGrade($grade);
            return $this->redirectToRoute('teacher_subject', ['id' => $subject->getId()]);
        }

        return $this->render('teacher/new.html.twig', [
            'grade' => $grade,
            'subject' => $subject,
            'student' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subject/{id}/edit-grade/{grade_id}", name="teacher_edit_grade", methods={"GET","POST"})
     */
    public function editGrade(Request $request, SubjectRepository $subjectRepository, GradeRepository $gradeRepository, GradeManager $gradeManager): Response
    {
        $subject = $subjectRepository->find($request->get('id'));
        $grade = $gradeRepository->find($request->get('grade_id'));

        if (!$gradeManager->checkRight($grade->getUser(), $subject)) {
            return $this->redirectToRoute('teacher_subject', ['id' => $subject->getId()]);
        }
        $form = $this->createForm(GradeTeacherType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gradeManager->saveGrade($grade);
            return $this->redirectToRoute('teacher_subject', ['id' => $subject->getId()]);
        }

        return $this->render('teacher/new.html.twig', [
            'grade' => $grade,
            'subject' => $subject,
            'student' => $grade->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_delete_grade", methods={"DELETE"})
     */
    public function delete(Request $request, Grade $grade): Response
    {
        if ($this->getUser()->getId() !== $grade->getSubject()->getUser()->getId()) {
            return $this->redirectToRoute('teacher_subject', ['id' => $grade->getSubject()->getId()]);
        }
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_subject', ['id' => $grade->getSubject()->getId()]);
    }

}
