<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Type\PreferredChoicesMaintainingCountryType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('reducedPrice', ChoiceType::class, array(

            'choices' => array(
                'Normal price' => false,
                'Reduced price' => true,
            ),

            'label' => 'Please, choose a ticket type',

            'required' => true))
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('country', PreferredChoicesMaintainingCountryType::class, array('data' => \Locale::getDefault(),
                'label' => false,
                'preferred_choices' => array('FR', 'EN', 'DE', 'ES', 'IT', 'BE', 'US'),
                'choices_as_values' => true,

                'attr' => ['class' => 'select']))
            ->add('birthdate', DateType::class, array(
                'format' => 'ddMMyyyy',
                'years' => range(date('Y') - 99, date('Y')),
                'label_attr' => ['class' => 'active'],
                'attr' => array(
                    'class' => 'active birthdate',
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'TicketType';
    }


}
