<?php

//Twig extension for calculating years between two dates.

namespace AppBundle\Twig;

class AgeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('age', array($this, 'ageCalculate')),
        );
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
