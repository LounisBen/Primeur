<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailService $mailService): Response
    {

        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setFirstname($this->getUser()->getFirstname())
                ->setEmail($this->getUser()->getEmail());
        }


        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());

            $contact = $form->getData();


            $manager->persist($contact);
            $manager->flush();

            //Email
            $mailService->sendEmail(
                $contact->getEmail(),
                $contact->getSubject(),
                'contact/contact.html.twig',
                ['contact' => $contact]
            );

            $this->addFlash(
                'success',
                'Votre demande a été envoyée avec succès!'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/envoyer-liste', name: 'envoyer_liste')]
    public function MailListe(ProductRepository $productRepository, Request $request, MailService $mailService, SessionInterface $session): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Rediriger vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour envoyer la liste.');
            return $this->redirectToRoute('app_login');
        }
        // Utilisez l'email de l'utilisateur connecté
        $userEmail = $user->getEmail();

        // Récupérer liste de produits ici
        $listeSession = $session->get('liste', []);
        $listeProduits = [];

        foreach ($listeSession as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $listeProduits[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        $mailService->sendEmail(
            'email@exemple.com',
            'Votre liste de produits',
            'liste/email.html.twig',
            ['liste' => $listeProduits],
            $userEmail
        );


        $this->addFlash('success', 'Votre liste a été envoyée par email.');
        return $this->redirectToRoute('app_home');
    }
}
