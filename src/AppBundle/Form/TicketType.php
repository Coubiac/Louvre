<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('birthdate', DateType::class, array(
        'widget' => 'single_text',
        'html5' => true,
        'attr' => array('class' => 'birthdate', 'max' => date('Y-m-d')),
        'format' => 'yyyy-MM-dd',
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
