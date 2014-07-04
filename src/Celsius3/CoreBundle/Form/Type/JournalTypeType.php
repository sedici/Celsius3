<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalTypeType extends MaterialTypeType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('volume')
                ->add('number')
                ->add('journal', null, array(
                    'empty_value' => 'Other',
                    'required' => false,
                ))
                ->add('other', null, array(
                    'required' => false,
                    'property_path' => 'other',
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Document\\JournalType',
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_journaltype';
    }

}
