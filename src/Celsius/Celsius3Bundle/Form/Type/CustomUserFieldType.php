<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomUserFieldType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('key')
                ->add('name')
                ->add('type')
                ->add('private')
        ;
        if (is_null($this->instance))
        {
            $builder->add('instance', null, array(
                'required' => false,
            ));
        } else
        {
            $builder->add('instance', 'instance_selector', array(
                'data' => $this->instance,
                'attr' => array(
                    'value' => $this->instance->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_customuserfieldtype';
    }

}
