<?php

namespace Celsius3\CoreBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailTemplateFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', null, array(
                    'required' => false,
                ))
                ->add('enabled', null, array(
                    'required' => false,
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('csrf_protection' => false,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_mailtemplatetype';
    }
}