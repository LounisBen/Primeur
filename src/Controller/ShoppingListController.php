<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShoppingListController extends AbstractController
{
    #[Route('/liste', name: 'app_liste')]
    public function liste(ProductRepository $productRepository, SessionInterface $session): Response
    {
        $listeSession = $session->get('liste', []);

        //On initialise les variables
        $liste = [];
        
        foreach ($listeSession as $id => $quantity) {
            $product = $productRepository->find($id);

            if($product) {
                $liste[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                
            }
        }
        return $this->render('liste/liste.html.twig', [
            'liste' => $liste,
            
        ]);
    }

    #[Route('/add/{id}', name: 'app_add')]
    public function add(Product $product, SessionInterface $session): Response
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère la liste existante
        $liste = $session->get('liste', []);

        //On ajoute le produit dans la liste s'il n'y est pas encore
        //sinon on incrémente sa quantité
        if (empty($liste[$id])) {
            $liste[$id] = 1;
        } else {
            $liste[$id]++;
        }

        $session->set('liste', $liste);
          
        return $this->redirectToRoute('app_liste');
    }

    #[Route('/remove/{id}', name: 'app_remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère la liste existante
        $liste = $session->get('liste', []);

        //On retire le produit dans la liste s'il n'y a qu'1 produit
        //sinon on décrémente 
        if (!empty($liste[$id])) {
            if ($liste[$id] > 1) {
                $liste[$id]--;
            }else{
                unset($liste[$id]);
            }
        }

        $session->set('liste', $liste);
          
        return $this->redirectToRoute('app_liste');
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(Product $product, SessionInterface $session): Response
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère la liste existante
        $liste = $session->get('liste', []);

        if (!empty($liste[$id])) {
            unset($liste[$id]);
        }

        $session->set('liste', $liste);
          
        return $this->redirectToRoute('app_liste');
    }

    #[Route('/empty', name: 'app_empty')]
    public function empty(SessionInterface $session): Response
    {
        // On vide la liste de la session
        $session->remove('liste');

        return $this->redirectToroute("app_liste");
    }
}
