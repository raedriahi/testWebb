<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Admin;
use App\Form\AdminType;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('/showAdmin',name:'showadmin')]
    public function showAdmin(EntityManagerInterface $entityManagerInterface): Response{

        $admin= $entityManagerInterface->getRepository(Admin::class)->findAll();
        return $this->render('admin/index.html.twig',['admins'=>$admin]);

    }

    #[Route('/addAdmin', 'newAdmin')]
    public function addAdmin(EntityManagerInterface $entityManagerInterface, Request $request ): Response
        {
            $admin =new Admin();
            $form = $this->createForm(AdminType::class, $admin);

            $form= $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManagerInterface->persist($admin);
                $entityManagerInterface->flush();

                return $this->redirectToRoute('showadmin');
            
            }

            return $this-> render('admin\addAdmin.html.twig', ['form' => $form->createView(),]);

        }

        #[Route('admin/{id}',name:'adminbyid')]
        public function findAdmin(EntityManagerInterface $entityManagerInterface,Admin $admin): Response
        {
            $admin= $entityManagerInterface->getRepository(className: Admin::class)->find($admin);

             return $this->render('admin/admin.html.twig',['admin'=>$admin]);

        }

        #[Route('/removeAdmin/{id}', name:'removeadmin')]
        public function removeAdmin(EntityManagerInterface $entityManagerInterface, Admin $admin, ): Response
        {
            $admin= $entityManagerInterface->getRepository(className: Admin::class)->find($admin);

            $entityManagerInterface->remove($admin);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('showadmin');
        }


        #[Route('editAdmin/{id}',name:'editAdmin')]
        public function editAdmin(EntityManagerInterface $entityManagerInterface, Admin $admin, Request $request):Response
        {


            $admin= $entityManagerInterface->getRepository(Admin::class)->find($admin);

            $form = $this->createForm(AdminType::class, $admin);

            $form=$form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManagerInterface->flush();
                return $this->redirectToRoute('showadmin');
            }

            return $this->render('admin/addAdmin.html.twig',['form' => $form->createView(),]);
        }
}
