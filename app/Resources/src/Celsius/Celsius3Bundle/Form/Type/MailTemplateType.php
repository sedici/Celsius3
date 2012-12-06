<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MailTemplateType extends AbstractType
{

    protected $instance;


    public function __construct($instance = null)
    {
        $this->instance = $instance;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('code')
            //    ->add('state', 'choice', array(
            //            'choices'   => array(1 => 'enabled', 0 => 'blocked'),
            //            'expanded'  => true,
            //            'multiple'  => false,
            //            'required'  => true,
            //        ))
                ->add('text', 'textarea', array(
                    'attr' => array(
                        'class' => 'tinymce',
                    ),
                ))
        ;
        if (!is_null($this->instance))
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
        return 'celsius_celsius3bundle_mailtemplatetype';
    }

}
