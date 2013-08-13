<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class InstanceType extends LegacyInstanceType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('url');
    }

    public function getName()
    {
        return 'celsius3_corebundle_instancetype';
    }

}
