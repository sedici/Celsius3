<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InstitutionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('abbreviation')
                ->add('website')
                ->add('address')
                ->add('parent', null, array(
                    'required' => false
                ))
                ->add('city')
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_institutiontype';
    }

}
