<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThesisTypeType extends MaterialTypeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('director')->add('degree');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array(
                                'data_class' => 'Celsius3\\CoreBundle\\Document\\ThesisType',));
    }

    public function getName()
    {
        return 'celsius3_corebundle_thesistype';
    }
}
