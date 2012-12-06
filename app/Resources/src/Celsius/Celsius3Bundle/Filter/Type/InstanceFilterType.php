<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstanceFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('abbreviation', null, array(
                    'required' => false,
                ))
                ->add('email', null, array(
                    'required' => false,
                ))
                ->add('institution', 'document', array(
                    'required' => false,
                    'class' => 'CelsiusCelsius3Bundle:Institution'
                ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_instancefiltertype';
    }

}
