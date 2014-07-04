<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CongressTypeType extends MaterialTypeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('place')->add('communication');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array(
                                'data_class' => 'Celsius3\\CoreBundle\\Document\\CongressType',));
    }

    public function getName()
    {
        return 'celsius3_corebundle_congresstype';
    }
}
