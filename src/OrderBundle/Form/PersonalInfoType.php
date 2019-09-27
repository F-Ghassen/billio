<?php

namespace OrderBundle\Form;

use OrderBundle\Entity\OrderInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customerFirstName', TextType::class, array(
                'required' => true,
                'label' => 'Nom',
            ))
            ->add('customerLastName', TextType::class, array(
                'required' => true,
                'label' => 'Prenom',
            ))
            ->add('customerEmail', TextType::class, array(
                'required' => true,
                'label' => 'Email',
            ))
            ->add('customerPhone', NumberType::class, array(
                'required' => true,
                'label' => 'Téléphone',
            ))
            ->add('customerCity', TextType::class, array(
                'required' => true,
                'label' => 'Ville',
            ))
            ->add('customerAddress', TextType::class, array(
                'required' => true,
                'label' => 'Adresse',
            ))
            ->add('postalCode', NumberType::class, array(
                'required' => true,
                'label' => 'Code postal',
            ))
            ->add('promo', TextType::class, array(
                'required' => false,
                'label' => 'Promo Code'
            ))
            ->add('save',  SubmitType::class, array(
                'label' => 'Valider la Commande',
                'attr' => array(
                    'class' => 'btn karl-checkout-btn'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OrderInfo::class,
        ));
    }
}