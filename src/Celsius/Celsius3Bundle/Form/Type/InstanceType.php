<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanceType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('abbreviation')
                ->add('website')
                ->add('title')
                ->add('email')
                ->add('url')
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_instancetype';
    }

}
