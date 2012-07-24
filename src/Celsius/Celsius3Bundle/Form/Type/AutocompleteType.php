<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AutocompleteType extends AbstractType
{

    protected $field;
    protected $model;
    protected $label;
    protected $parent;

    public function __construct($field, $model, $parent, $label)
    {
        $this->field = $field;
        $this->model = $model;
        $this->parent = $parent;
        $this->label = $label;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add($this->field, 'user_selector', array(
                    'attr' => array(
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
                ->add($this->field . '_autocomplete', 'text', array(
                    'attr' => array(
                        'class' => 'autocomplete',
                        'target' => $this->model,
                    ),
                    'property_path' => false,
                    'label' => $this->label,
                ))
        ;
        $builder->setAttribute('label', false);
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\' . $this->parent,
        );
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_autocompletetype';
    }

}
