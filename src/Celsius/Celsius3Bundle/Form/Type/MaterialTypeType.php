<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MaterialTypeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('authors')
                ->add('year')
                ->add('startPage')
                ->add('endPage')
        ;
        $builder->setAttribute('label', false);
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\MaterialType',
        );
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_materialtypetype';
    }

}
