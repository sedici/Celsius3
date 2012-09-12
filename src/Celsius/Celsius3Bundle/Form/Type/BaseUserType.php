<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseUserType extends AbstractType
{

    protected $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('surname')
                ->add('birthdate', 'birthday', array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array('class' => 'date')
                ))
                ->add('username')
                ->add('email', 'email')
                ->add('address')
        ;
        if (is_null($this->instance))
        {
            $builder->add('instance');
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
        return 'celsius_celsius3bundle_baseusertype';
    }

}
