<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionFilterType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('abbreviation', null, array(
                    'required' => false,
                ))
                ->add('parent', 'document', array(
                    'required' => false,
                    'class' => 'CelsiusCelsius3Bundle:Institution',
                ))
                ->add('city', 'document', array(
                    'required' => false,
                    'class' => 'CelsiusCelsius3Bundle:City',
                ))
        ;
        if (is_null($this->instance))
        {
            $builder
                    ->add('instance', 'document', array(
                        'required' => false,
                        'class' => 'CelsiusCelsius3Bundle:Instance',
                        'label' => 'Owning Instance',
                    ))
                    ->add('celsiusInstance', 'document', array(
                        'required' => false,
                        'class' => 'CelsiusCelsius3Bundle:Instance',
                        'label' => 'Celsius Instance',
                    ))
            ;
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
        return 'celsius_celsius3bundle_institutionfiltertype';
    }

}
