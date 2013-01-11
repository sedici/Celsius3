<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('file', 'file', array(
                    'required' => false,
                ))
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_filetype';
    }

}
