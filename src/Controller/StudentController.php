<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request; 

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    

    #[Route('/show', name: 'show')]
    public function showStudent(EntityManagerInterface $entityManager): Response
    {
        $students = $entityManager->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }



    #[Route('/show/{id}', name: 'student')]
    public function findStudent(EntityManagerInterface $entityManager, Student $student): Response
        {
          $student = $entityManager->getRepository(Student::class)->find($student);
            return $this->render('student/show.html.twig', [
                'student' => $student,
            ] );
        }


    #[Route('/add', name: 'new_student')]
    public function addStudent(EntityManagerInterface $entityManager, Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('show');
        }

        return $this->render('student/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}','delete')]
    public function deleteStudent(EntityManagerInterface $entityManager,Student $student):Response
        {
            $student=$entityManager->getRepository(Student::class)->find($student);
            $entityManager->remove($student);
            $entityManager->flush();
            return $this->redirectToRoute('show');
        }


    #[Route('/edit/{id}',name:'edit')]
    public function edit(EntityManagerInterface $entityManagerInterface,Student $student, Request $request) :Response
    {
        $student=$entityManagerInterface->getRepository(Student::class)->find($student);
        
        $form = $this->createForm(StudentType::class, $student);

        $form= $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManagerInterface->flush();
            return $this->redirectToRoute('show');
        }
        return $this ->render('student/add.html.twig',['form' => $form->createView(),]);

    }


}
