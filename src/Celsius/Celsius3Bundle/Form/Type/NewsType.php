<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsType extends AbstractType
{

    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('text')
                ->add('date')
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
        return 'celsius_celsius3bundle_newstype';
    }

}
