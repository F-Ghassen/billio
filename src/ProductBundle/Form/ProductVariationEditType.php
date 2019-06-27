<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\ProductVariation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('images', CollectionType::class, array(
                'entry_type' => ProductImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductVariation::class,
        ));
    }
}