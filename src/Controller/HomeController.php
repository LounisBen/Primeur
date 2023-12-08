<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        
        return $this->render('home/index.html.twig', [
            
        ]);
    }
            
    #[Route('/categorie', name: 'app_category', methods: ['GET'])]
    public function category(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        
        return $this->render('home/category.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    
    
    #[Route('/produit/{id}', name: 'app_product')]
    public function categorie(Category $category,  PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $category->getProducts(),
            $request->query->getInt('page', 1),
            6
        );
        
        return $this->render('home/product.html.twig', [
            'category' => $category,
            'products' => $products,
          
        ]);
       
    }

    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(Product $product): Response
    {
        return $this->render('home/detail.html.twig', [
            'detail' => $product,
            
            
          
        ]);
       
    }
}
