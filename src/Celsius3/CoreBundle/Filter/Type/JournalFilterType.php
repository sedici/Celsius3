<?php

namespace Celsius3\CoreBundle\Filter\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalFilterType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null,
                        array('required' => false,))
                ->add('abbreviation', null,
                        array('required' => false,))
                ->add('responsible', null,
                        array('required' => false,))
                ->add('ISSN', null,
                        array('required' => false,))
                ->add('ISSNE', null,
                        array('required' => false,))
                ->add('frecuency', null,
                        array('required' => false,));

        if (is_null($this->instance)) {
            $builder
                    ->add('instance', 'document',
                            array('required' => false,
                                    'class' => 'Celsius3CoreBundle:Instance',));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('csrf_protection' => false,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_journalFiltertype';
    }

}
