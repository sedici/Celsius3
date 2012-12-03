<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomUserFieldFilterType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('key', null, array(
                    'required' => false,
                ))
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('type', null, array(
                    'required' => false,
                ))
                ->add('private', null, array(
                    'required' => false,
                ))
        ;
        if (is_null($this->instance))
        {
            $builder->add('instance', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:Instance',
            ));
        }
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_customuserfieldfiltertype';
    }

}
