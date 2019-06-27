<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductImage;
use ProductBundle\Entity\ProductVariation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductVariationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', TextType::class)
            ->add('s', IntegerType::class)
            ->add('m', IntegerType::class)
            ->add('l', IntegerType::class)
            ->add('xl', IntegerType::class)
            ->add('xxl', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductVariation::class,
        ));
    }
}