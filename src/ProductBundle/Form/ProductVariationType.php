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
            ->add('color', TextType::class, array('required' => false))
            ->add('s', IntegerType::class, array('required' => false))
            ->add('m', IntegerType::class, array('required' => false))
            ->add('l', IntegerType::class, array('required' => false))
            ->add('xl', IntegerType::class, array('required' => false))
            ->add('xxl', IntegerType::class, array('required' => false))
            ->add('sizeJean29', IntegerType::class, array('required' => false))
            ->add('sizeJean30', IntegerType::class, array('required' => false))
            ->add('sizeJean31', IntegerType::class, array('required' => false))
            ->add('sizeJean32', IntegerType::class, array('required' => false))
            ->add('sizeJean33', IntegerType::class, array('required' => false))
            ->add('sizeJean34', IntegerType::class, array('required' => false))
            ->add('sizeJean36', IntegerType::class, array('required' => false))
            ->add('sizeJean38', IntegerType::class, array('required' => false))
            ->add('sizeMoc40', IntegerType::class, array('required' => false))
            ->add('sizeMoc41', IntegerType::class, array('required' => false))
            ->add('sizeMoc42', IntegerType::class, array('required' => false))
            ->add('sizeMoc43', IntegerType::class, array('required' => false))
            ->add('sizeMoc44', IntegerType::class, array('required' => false))
            ->add('sizeMoc45', IntegerType::class, array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductVariation::class,
        ));
    }
}