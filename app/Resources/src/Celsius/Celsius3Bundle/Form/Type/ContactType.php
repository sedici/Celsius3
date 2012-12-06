<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{

    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('surname')
                ->add('email')
                ->add('address')
                ->add('user')
                ->add('type')
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
        return 'celsius_celsius3bundle_contacttype';
    }

}
