<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'Your mail adress',

            ))
            ->add('dateOfVisit', DateType::class, array(
            'widget' => 'single_text',
            'html5' => true,
            'attr' => array('class' => 'datepicker', 'min' => date('Y-m-d')),
            'format' => 'yyyy-MM-dd',
        ))
            ->add('fullDayTicket', ChoiceType::class, array(
                'choices' => array(
                    'Full day ticket' => true,
                    'Half day ticket : From 14 hours' => false,
                ),
                'label' => 'Please, choose a ticket type',
                'empty_data' => '',



            ))
            ->add('tickets', CollectionType::class, array(
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'attr' => array(
                    'class' => 'ticketform',
                ),


            ))
            ->add('submit', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-large waves-effect waves-light deep-orange accent-3')
            ));;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_order';
    }


}
