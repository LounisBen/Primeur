<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailService $mailService): Response {
        
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

            return $this->redirectToRoute('app_contact');
        }
        
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}