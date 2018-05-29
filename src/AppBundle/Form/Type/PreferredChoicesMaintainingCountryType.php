<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

// Ce form type sert à garder l'ordre des pays dans le country type.
class PreferredChoicesMaintainingCountryType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if ($options['preferred_choices']) {
            $newPreferredChoices = [];
            foreach ($options['preferred_choices'] as $key) {
                $choice = $this->findChoiceView($view, $key);
                if (!$choice) {
                    continue;
                }
                $newPreferredChoices[$key] = $choice;
            }
            $view->vars['preferred_choices'] = $newPreferredChoices;
        }
    }

    private function findChoiceView(FormView $view, $keyToFind)
    {
        foreach ($view->vars['preferred_choices'] as $choice) {
            if ($choice->value == $keyToFind) {
                return $choice;
            }
        }
    }

    public function getParent()
    {
        return CountryType::class;
    }

    public function getName()
    {
        return 'country_maintaining_preferred';
    }
}
