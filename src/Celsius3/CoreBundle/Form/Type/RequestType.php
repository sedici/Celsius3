<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\BaseUser;
use JMS\TranslationBundle\Annotation\Ignore;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ODM\MongoDB\DocumentRepository;

class RequestType extends AbstractType
{

    protected $instance;
    protected $user;

    public function __construct(Instance $instance, BaseUser $user = null)
    {
        $this->instance = $instance;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', 'choice', array(
                    'choices' => array(
                        /** @Ignore */ OrderManager::TYPE__SEARCH => ucfirst(OrderManager::TYPE__SEARCH),
                        /** @Ignore */ OrderManager::TYPE__PROVISION => ucfirst(OrderManager::TYPE__PROVISION),
                    ),
                ))
                ->add('comments', 'textarea', array(
                    'required' => false,
                ))
                ->add('owner', 'celsius3_corebundle_user_selector', array(
                    'attr' => array(
                        'value' => (!is_null($this->user)) ? $this->user->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
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
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Document\\Request',
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_requesttype';
    }

}

