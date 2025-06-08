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

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameProd', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du produit est requis.']),
                ],
            ])
            ->add('imgProd', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une image.']),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/webp',
                            'image/avif',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Formats autorisés : AVIF, WebP, JPG, PNG',
                    ]),
                ],
            ])
            ->add('numeroProd', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro du produit est requis.']),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le numéro doit contenir uniquement des chiffres.',
                    ]),
                ],
            ])
            ->add('priceProd', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis.']),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Le prix doit être un nombre.',
                    ]),
                ],
            ])
            ->add('descripProd', null, [
                'required' => false, // champ optionnel
            ])
            ->add('galleryProd', FileType::class, [
                'label' => 'Galerie d\'images (max 5)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new Count([
                        'max' => 5,
                        'maxMessage' => 'Vous ne pouvez uploader que {{ limit }} images maximum.',
                    ]),
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '5M',
                                'mimeTypes' => [
                                    'image/webp',
                                    'image/avif',
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Formats autorisés : AVIF, WebP, JPG, PNG',
                            ]),
                        ],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
