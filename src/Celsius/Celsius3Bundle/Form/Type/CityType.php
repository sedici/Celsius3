<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('postalCode')
            ->add('country')
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_citytype';
    }
}
