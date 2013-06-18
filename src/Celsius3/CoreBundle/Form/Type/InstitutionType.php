<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;

class InstitutionType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('abbreviation')
                ->add('website', null,
                        array('required' => false))
                ->add('address', null,
                        array('required' => false))
                ->add('parent', null,
                        array('required' => false))
                ->add('city', null,
                        array('required' => false))
                ->add('country', null,
                        array('required' => false));
        if (is_null($this->instance)) {
            $builder
                    ->add('instance', null,
                            array('required' => false,
                                    'label' => 'Owning Instance',))
                    ->add('celsiusInstance', null,
                            array('required' => false,
                                    'label' => 'Celsius Instance',));
        } else {
            $builder
                    ->add('instance', 'instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_institutiontype';
    }

}
