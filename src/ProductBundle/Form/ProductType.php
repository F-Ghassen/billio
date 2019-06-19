<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\Brand;
use ProductBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('category', ChoiceType::class, array(
                'choices' => array(
                    'TShirt' => 'TShirt',
                    'Casquettes' => 'Casquettes',
                    'Mocassin' => 'Mocassin',
                    'Polo' => 'Polo',
                    'Chemises' => 'Chemises',
                    'Jeans' => 'Jeans',
                ),
                'placeholder' => 'Select',
            ))
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('imageFile', VichImageType::class, array(
                'download_link'     => false,
                'required'    => false,
                'allow_delete' => false,
            ))
            ->add('imageFile1', VichImageType::class, array(
                'download_link'     => false,
                'required'    => false,
                'allow_delete' => false,
            ))
            ->add('imageFile2', VichImageType::class, array(
                'download_link'     => false,
                'required'    => false,
                'allow_delete' => false,
            ))
            ->add('imageFile3', VichImageType::class, array(
                'download_link'     => false,
                'required'    => false,
                'allow_delete' => false,
            ))
            ->add('save',  SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }
}