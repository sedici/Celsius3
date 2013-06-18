<?php

namespace Celsius3\CoreBundle\Filter\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstanceFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null,
                        array('required' => false,))
                ->add('abbreviation', null,
                        array('required' => false,))
                ->add('email', null,
                        array('required' => false,))
                ->add('institution', 'document',
                        array('required' => false,
                                'class' => 'Celsius3CoreBundle:Institution'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('csrf_protection' => false,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_instancefiltertype';
    }

}
