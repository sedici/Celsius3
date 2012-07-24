<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;

    public function __construct($instance, $material = null)
    {
        $this->instance = $instance;
        $this->material = (is_null($material)) ? new JournalTypeType() : $material;

        $class = explode('\\', get_class($this->material));
        $this->preferredMaterial = lcfirst(str_replace('Type', '', end($class)));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getData())
        {
            $owner = $builder->getData()->getOwner();
        } else
        {
            $owner = '';
        }

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
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
                ->add('owner_autocomplete', 'text', array(
                    'attr' => array(
                        'class' => 'autocomplete',
                        'target' => 'BaseUser',
                        'value' => $owner,
                    ),
                    'mapped' => false,
                    'label' => 'Owner',
                ))
                ->add('instance', 'instance_selector', array(
                    'data' => $this->instance,
                    'attr' => array(
                        'value' => $this->instance->getId(),
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
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_ordertype';
    }

}
