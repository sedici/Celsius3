<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ODM\MongoDB\DocumentRepository;

class BaseUserType extends RegistrationFormType
{

    private $editing;

    public function __construct(ContainerInterface $container, $class, Instance $instance, $editing = false)
    {
        parent::__construct($container, $class);
        $this->instance = $instance;
        $this->editing = $editing;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('enabled', null, array(
                    'required' => false,
                ))
                ->add('locked', null, array(
                    'required' => false,
                ))
        ;
        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder
                    ->add('instance', null, array(
                        'query_builder' => function (DocumentRepository $repository) {
                            return $repository->findAllExceptDirectory();
                        },
                    ))
            ;
        } else {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector', array(
                        'data' => $this->instance,
                        'attr' => array(
                            'value' => $this->instance->getId(),
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }

        if ($this->editing) {
            $builder->remove('plainPassword');
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_baseusertype';
    }

}
