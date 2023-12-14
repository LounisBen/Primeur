<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Form\PromoType;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{
    #[Route('/promo', name: 'app_promo', methods: ['GET', 'POST'])]
    public function index(PromoRepository $promoRepository): Response
    {
        
        $promos = $promoRepository->findAll();
        
        return $this->render('promo/index.html.twig', [
            'promos' => $promos,
            
        
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/promo/creation', 'new_promo', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $promo = new Promo();
        $form = $this->createForm(PromoType::class, $promo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promo = $form->getData();
            

            $manager->persist($promo);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre promo a été créée avec succès !'
            );

            return $this->redirectToRoute('app_promo');
        }
        
            return $this->render('promo/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/promo/edition/{id}', 'edit_promo', methods: ['GET', 'POST'])]
    public function edit(Promo $promo, Request $request, EntityManagerInterface $manager): Response {
        
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promo = $form->getData();
        
            $manager->persist($promo);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre promo a été modifiée avec succès !'
            );
            return $this->redirectToRoute('app_promo');
        }
        return $this->render('promo/edit.html.twig', [
            'form' => $form->createView(),
            'promo' => $promo,
        
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/promo/suppression/{id}', 'delete_promo', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Promo $promo, Request $request): Response {
        
            
            
            $manager->remove($promo);
            $manager->flush();
    
            $this->addFlash('success', 'Votre promo a été supprimée avec succès !');
            
    
        return $this->redirectToRoute('app_promo');
    }
}
