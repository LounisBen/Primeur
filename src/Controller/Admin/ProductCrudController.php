<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    private $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud):Crud
    {
        return $crud
        ->setEntityLabelInPlural('Products')
        ->setEntityLabelInSingular('Product')
        ->setPageTitle('index', 'Primeur - Administration des produits')
        ->setPageTitle('new', 'Primeur - Ajout d\'un produit')
        ->setPageTitle('edit', function (Product $product) 
            {
                return 'Modification du produit : ' . $product->getName();
            })
        ->setPageTitle('detail', function (Product $product) 
            {
                return $product->getName();
            })
        ->setDefaultSort(['id' => 'ASC'])
        ->setPaginatorPageSize(15); 
    }

    public function configureFields(string $pageName): iterable
    {
        // On recupère toutes les SousCategories existantes (pour les choix de la SousCategorie quand on modifie/ajoute un Produit)
        $categories = $this->manager->getRepository(Category::class)->findAll();

        if ($pageName=="new") 
        {
            return [
                TextField::new('name'),
                TextareaField::new('description'),
                AssociationField::new('category')
                    ->setFormTypeOptions([
                        'multiple' => false,
                        'class' => Category::class,
                        'choices' => $categories,
                    ]),
                TextField::new('imageFile')->setFormType(VichImageType::class),
            ];
        } 
        elseif ($pageName == "edit") 
        {
            return [
                TextField::new('name'),
                TextareaField::new('description'),
                AssociationField::new('category')
                    ->setFormTypeOptions([
                        'multiple' => false,
                        'class' => Category::class,
                        'choices' => $categories,
                    ]),
                TextField::new('imageFile')->setFormType(VichImageType::class),
            ];
        } 
        elseif ($pageName == "detail") 
        {
            return [
                IdField::new('id'),
                TextField::new('name'),
                TextField::new('category'),
                TextareaField::new('description'),
                ImageField::new('imageName', 'Image')->setBasePath('/images/produit'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('updatedAt'),
            ];
        } 
        else // page : index
        { 
            return [
                IdField::new('id'),
                TextField::new('name'),
                TextField::new('category'),
                ImageField::new('imageName', 'Image')->setBasePath('/images/produit')
            ];
        }
    }
}