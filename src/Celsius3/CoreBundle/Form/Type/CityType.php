<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;

class CityType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('postalCode')->add('country');
        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder->add('instance');
        } else {
            $builder->add('instance', 'celsius3_corebundle_instance_selector', array(
                'data' => $this->instance,
                'attr' => array(
                    'value' => $this->instance->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_citytype';
    }

}
