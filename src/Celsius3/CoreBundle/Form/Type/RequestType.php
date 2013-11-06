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

    private $instance;
    private $user;
    private $operator;
    private $librarian;

    public function __construct(Instance $instance, BaseUser $user = null, BaseUser $operator = null, $librarian = false)
    {
        $this->instance = $instance;
        $this->user = $user;
        $this->operator = $operator;
        $this->librarian = $librarian;
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

        if ($this->librarian) {
            $builder
                    ->add('target', 'choice', array(
                        'choices' => array(
                            'me' => 'Me',
                            'other' => 'Other'
                        ),
                        'mapped' => false,
                    ))
                    ->add('librarian', 'celsius3_corebundle_user_selector', array(
                        'attr' => array(
                            'readonly' => 'readonly',
                        ),
                    ))
                    ->add('owner_autocomplete', 'text', array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => $this->user,
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                    ))
            ;
        }

        if (!is_null($this->operator)) {
            $builder
                    ->add('owner_autocomplete', 'text', array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => $this->user,
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                    ))
                    ->add('operator', 'celsius3_corebundle_user_selector', array(
                        'attr' => array(
                            'value' => (!is_null($this->operator)) ? $this->operator->getId() : '',
                            'class' => 'container',
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }

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

