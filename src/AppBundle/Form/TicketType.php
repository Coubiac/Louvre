<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\PreferredChoicesMaintainingCountryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reducedPrice', ChoiceType::class, [

            'choices' => [
                'Normal price'  => false,
                'Reduced price' => true,
            ],

            'label' => 'Please, choose a ticket type',

            'required' => true, ])
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('country', PreferredChoicesMaintainingCountryType::class, ['data' => \Locale::getDefault(),
                'label'                                                             => false,
                'preferred_choices'                                                 => ['FR', 'EN', 'DE', 'ES', 'IT', 'BE', 'US'],
                'choices_as_values'                                                 => true,

                'attr' => ['class' => 'select'], ])
            ->add('birthdate', DateType::class, [
                'format'      => 'ddMMyyyy',
                'years'       => range(date('Y') - 99, date('Y')),
                'label_attr'  => ['class' => 'active'],
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day', ],
                'attr' => [
                    'class' => 'active birthdate',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Ticket',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'TicketType';
    }
}
