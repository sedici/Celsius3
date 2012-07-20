<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BaseUserType extends AbstractType
{

    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('surname')
                ->add('birthdate', 'birthday')
                ->add('username')
                ->add('email', 'email')
                ->add('address')
                ->add('instance', 'instance_selector', array(
                    'data' => $this->instance,
                    'attr' => array(
                        'value' => $this->instance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_baseusertype';
    }

}
