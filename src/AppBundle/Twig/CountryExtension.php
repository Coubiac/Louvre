<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;

// Extension pour afficher le Pays en toutes lettres à partir du countrycode
class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('countryName', [$this, 'countryName']),
        ];
    }

    public function countryName($countryCode)
    {
        return Intl::getRegionBundle()->getCountryName($countryCode);
    }

    public function getName()
    {
        return 'CountryExtension';
    }
}
