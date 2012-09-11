<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('url')
                ->add('comments', 'textarea', array(
                    'required' => false,
                ))
                ->add('institution', null, array(
                    'required' => false,
                ))
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_catalogtype';
    }

}
