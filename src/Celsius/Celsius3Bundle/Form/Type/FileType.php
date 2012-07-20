<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FileType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('name', null, array(
                    'read_only' => true,
                ))
                ->add('comments', 'textarea', array(
                    'required' => false,
                ));
        if (!$builder->getData()->getId())
        {
            $builder->add('file', 'file', array(
                'required' => false,
            ));
        }
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_filetype';
    }

}
