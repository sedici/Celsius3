<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTransformType extends AbstractType
{

    protected $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!is_null($this->instance))
        {
            $builder->add('type', 'choice', array(
                'choices' => array(
                    'baseuser' => 'User',
                    'librarian' => 'Librarian',
                    'admin' => 'Admin',
                ),
                'expanded' => true,
            ));
        } else
        {
            $builder->add('type', 'choice', array(
                'choices' => array(
                    'baseuser' => 'User',
                    'librarian' => 'Librarian',
                    'admin' => 'Admin',
                    'superadmin' => 'Superadmin',
                ),
                'expanded' => true,
            ));
        }
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_transformusertype';
    }

}