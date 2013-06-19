<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;

class ContactType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('surname')->add('email')->add('address')
                ->add('user')->add('type')
                ->add('instance', 'celsius3_corebundle_instance_selector',
                        array('data' => $this->instance,
                                'attr' => array(
                                        'value' => $this->instance->getId(),
                                        'readonly' => 'readonly',),));
    }

    public function getName()
    {
        return 'celsius3_corebundle_contacttype';
    }

}
