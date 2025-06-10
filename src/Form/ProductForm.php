<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class ProductForm extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nameProd', null, [
            'label' => 'Nom du produit',
            'attr' => ['class' => 'form-field'],
            'constraints' => [ /* ... */ ],
        ])
        ->add('imgProd', FileType::class, [
            'attr' => ['class' => 'form-field'],
            'label' => 'Image du produit',
            'mapped' => false,
            'required' => true,
            'constraints' => [ /* ... */ ],
        ])
        ->add('numeroProd', null, [
            'label' => 'Numero du Produit',
            'attr' => ['class' => 'form-field'],
            'constraints' => [ /* ... */ ],
        ])
        ->add('priceProd', null, [
            'label' => 'Prix',
            'attr' => ['class' => 'form-field'],
            'constraints' => [ /* ... */ ],
        ])
        ->add('descripProd', null, [
            'label' => 'Description',
            'attr' => ['class' => 'form-field'],
            'required' => false,
        ])
        ->add('galleryProd', FileType::class, [
            'attr' => ['class' => 'form-field'],
            'label' => 'Galerie d\'images (max 5)',
            'mapped' => false,
            'required' => false,
            'multiple' => true,
            'constraints' => [ /* ... */ ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
