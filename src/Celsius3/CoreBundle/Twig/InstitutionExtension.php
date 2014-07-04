<?php

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Document\Institution;

class InstitutionExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'full_name' => new \Twig_Function_Method($this, 'fullName'),
            'get_country' => new \Twig_Function_Method($this, 'getCountry'),
            'get_city' => new \Twig_Function_Method($this, 'getCity'),
        );
    }

    public function fullName(Institution $institution = null)
    {
        return $institution ? $institution->getFullName() : '';
    }

    public function getCountry(Institution $institution = null)
    {
        return $institution ? $institution->getCountry() ? $institution->getCountry() : ''  : '';
    }

    public function getCity(Institution $institution = null)
    {
        return $institution ? $institution->getCity() ? $institution->getCity() : ''  : '';
    }

    public function getName()
    {
        return 'celsius3_core.institution_extension';
    }

}
