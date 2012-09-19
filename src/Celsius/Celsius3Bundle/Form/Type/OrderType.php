<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;
    protected $user;

    public function __construct($instance = null, $material = null, $user = null)
    {
        $this->instance = $instance;
        $this->material = (is_null($material)) ? new JournalTypeType() : $material;

        $class = explode('\\', get_class($this->material));
        $this->preferredMaterial = lcfirst(str_replace('Type', '', end($class)));

        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', 'choice', array(
                    'choices' => array(
                        0 => 'Provision',
                        1 => 'Search',
                    ),
                ))
                ->add('comments', 'textarea', array(
                    'required' => false
                ))
                ->add('owner', 'user_selector', array(
                    'attr' => array(
                        'value' => (!is_null($this->user)) ? $this->user->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
                ->add('materialDataType', 'choice', array(
                    'choices' => array(
                        'journal' => 'Journal',
                        'book' => 'Book',
                        'congress' => 'Congress',
                        'thesis' => 'Thesis',
                        'patent' => 'Patent',
                    ),
                    'mapped' => false,
                    'data' => $this->preferredMaterial,
                    'label' => 'Material Type'
                ))
                ->add('materialData', $this->material)
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
        return 'celsius_celsius3bundle_ordertype';
    }

}
