<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JournalType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('abbreviation')
                ->add('responsible')
                ->add('ISSN')
                ->add('ISSNE')
                ->add('frecuency')
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
        return 'celsius_celsius3bundle_journaltype';
    }

}
