<?php

namespace Celsius\Celsius3Bundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BaseUserFilterType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('name', 'text')
                ->add('surname', 'text')
                ->add('username', 'text')
                ->add('email', 'text')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
        );
    }

    public function getName()
    {
        return 'baseuserfiltertype';
    }

}
