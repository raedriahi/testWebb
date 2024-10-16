<?php

namespace App\Controller;

use App\Entity\Aeroport;
use App\Form\AeroportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;



class AeroportController extends AbstractController
{
    #[Route('/aeroport', name: 'app_aeroport')]
    public function index(): Response
    {
        return $this->render('aeroport/index.html.twig', [
            'controller_name' => 'AeroportController',
        ]);
    }

    
    #[Route('/showAeroport', name: 'showAeroport')]
    public function showAeroport(EntityManagerInterface $entityManager): Response
    {
        $aeroports = $entityManager->getRepository(Aeroport::class)->findAll();
        return $this->render('aeroport/showAeroport.html.twig', [
            'aeroports' => $aeroports,
        ]);
    }

    #[Route('/addAeroport', 'addAeroport')]
    public function addAeroport(EntityManagerInterface $entityManagerInterface, Request $request ): Response
        {
            $aeroport =new Aeroport();
            $form = $this->createForm(AeroportType::class, $aeroport);

            $form= $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManagerInterface->persist($aeroport);
                $entityManagerInterface->flush();

                return $this->redirectToRoute('showAeroport');
            
            }

            return $this-> render('aeroport/add.html.twig', ['form' => $form->createView(),]);

        }

        #[Route('editAeroport/{id}',name:'editAeroport')]
        public function editAeroport(EntityManagerInterface $entityManagerInterface, Aeroport $aeroport, Request $request):Response
        {


            $admin= $entityManagerInterface->getRepository(Aeroport::class)->find($aeroport);

            $form = $this->createForm(AeroportType::class, $admin);

            $form=$form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManagerInterface->flush();
                return $this->redirectToRoute('showAeroport');
            }

            return $this->render('aeroport/add.html.twig',['form' => $form->createView(),]);
        }


}
