<?php

namespace MessageBundle\Form;

use MessageBundle\Entity\MailingList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailingListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email Address',
                    'class' => 'form-control',
                    'label' => 'Email',
                    'required' => true,
                )
            ))
            ->add('save',  SubmitType::class, array(
                'label' => 'Send',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MailingList::class,
        ));
    }
}