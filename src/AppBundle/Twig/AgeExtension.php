<?php

//Twig extension for calculating years between two dates.

namespace AppBundle\Twig;

//Extension permettant d'afficher l'age du visiteur à la date de visite
class AgeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('age', [$this, 'ageCalculate']),
        ];
    }

    public function ageCalculate(\DateTime $bithdayDate, \DateTime $dateOfVisit)
    {
        $interval = $dateOfVisit->diff($bithdayDate);

        return $interval->y;
    }

    public function getName()
    {
        return 'age_extension';
    }
}
