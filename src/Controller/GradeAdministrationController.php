<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use App\Service\GradeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/grade")
 */
class GradeAdministrationController extends AbstractController
{
    /**
     * @Route("/", name="grade_administration_index", methods={"GET"})
     */
    public function index(GradeRepository $gradeRepository): Response
    {
        return $this->render('grade/index.html.twig', [
            'grades' => $gradeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="grade_administration_new", methods={"GET","POST"})
     */
    public function new(Request $request, GradeManager $gradeManager): Response
    {
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gradeManager->saveGrade($grade);

            return $this->redirectToRoute('grade_administration_index');
        }

        return $this->render('grade/new.html.twig', [
            'grade' => $grade,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grade_administration_show", methods={"GET"})
     */
    public function show(Grade $grade): Response
    {
        return $this->render('grade/show.html.twig', [
            'grade' => $grade,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grade_administration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Grade $grade, GradeManager $gradeManager): Response
    {
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gradeManager->saveGrade($grade);

            return $this->redirectToRoute('grade_administration_index', [
                'id' => $grade->getId(),
            ]);
        }

        return $this->render('grade/edit.html.twig', [
            'grade' => $grade,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grade_administration_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Grade $grade): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grade_administration_index');
    }
}
