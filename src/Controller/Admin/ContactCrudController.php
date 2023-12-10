<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Demandes de contact')
            ->setEntityLabelInSingular('Demande de contact')
            ->setPageTitle('index', 'Primeur de Laigneville - Administration des demandes de contact')
            
            ->setPaginatorPageSize(15)
            
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');

            

    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('subject'),
            TextareaField::new('message')
            ->setFormType(CKEditorType::class)
            ->hideOnIndex(),
            DateTimeField::new('createdAt'),
        ];
    }
    
}
