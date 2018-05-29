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
            ->add('email', EmailType::class, [
                'label' => 'Your mail adress',

            ])
            ->add('dateOfVisit', DateType::class, [
                'widget' => 'single_text',
                'html5'  => true,
                'attr'   => ['class' => 'datepicker', 'min' => date('d-m-Y'), 'id' => 'dateOfVisit'],
                'format' => 'dd/MM/yyyy',
            ])
            ->add('fullDayTicket', ChoiceType::class, [
                'choices' => [
                    'Full day ticket'                 => true,
                    'Half day ticket (From 14 hours)' => false,
                ],
                'label' => 'Please, choose a ticket type',
                'attr'  => ['class' => 'ticketType'],

            ])
            ->add('tickets', CollectionType::class, [
                'entry_type'    => TicketType::class,
                'allow_add'     => true,
                'prototype'     => true,
                'allow_delete'  => true,
                'label'         => false,
                'by_reference'  => false,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'ticketform',
                ],

            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-large waves-effect waves-light deep-orange accent-3'],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Order',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_order';
    }
}
