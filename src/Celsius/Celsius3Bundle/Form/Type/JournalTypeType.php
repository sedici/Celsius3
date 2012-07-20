<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class JournalTypeType extends MaterialTypeType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('volume')
                ->add('number')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\JournalType',
        );
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_journaltype';
    }

}
