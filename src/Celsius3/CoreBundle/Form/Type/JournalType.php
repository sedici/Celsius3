<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;

class JournalType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('abbreviation')->add('responsible')
                ->add('ISSN')->add('ISSNE')->add('frecuency');

        if (is_null($this->instance)) {
            $builder
                    ->add('instance', null,
                            array('required' => false,));
        } else {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_journaltype';
    }

}
