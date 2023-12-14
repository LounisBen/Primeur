<?php

namespace App\Form;

use App\Entity\Promo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PromoType extends AbstractType
{
    
    private $token;

    public function __construct(TokenStorageInterface  $token)
    {
        $this->token = $token;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength' => '30'
           ],
           'label' => 'Titre',
           'label_attr' => [
                'class'=> 'form-label mt-4'
           ],
           'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 30]),
                new Assert\NotBlank()
           ]
        ])
        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 5
            ],
            'label' => 'Description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank() 
            ]
    ])
        ->add('prix', MoneyType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
           'label' => 'Prix ',
           'label_attr' => [
                'class'=> 'form-label mt-4'
           ],
           'constraints' => [
                new Assert\Positive()
                
           ]

        ])
        ->add('imageFile', VichImageType::class, [
            'label' => 'Image de la promo',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'required' => false
        ])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ],
            'label' => 'Créer ma promo'
        ]);
       
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promo::class,
        ]);
    }
}
