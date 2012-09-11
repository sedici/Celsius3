<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JournalType extends AbstractType
{
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
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_journaltype';
    }
}
