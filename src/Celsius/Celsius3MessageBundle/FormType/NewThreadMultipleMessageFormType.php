<?php

namespace Celsius\Celsius3MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\MessageBundle\FormType\NewThreadMultipleMessageFormType as BaseNewThreadMultipleMessageFormType;

class NewThreadMultipleMessageFormType extends BaseNewThreadMultipleMessageFormType
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->container->get('security.context')->isGranted('ROLE_ADMIN');
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($isAdmin)
        {
            $builder
                    ->add('recipients', 'recipients_selector_custom', array(
                        'class' => 'Celsius\\Celsius3Bundle\\Document\\BaseUser',
                        'property' => 'username',
                        'multiple' => true,
                        'query_builder' => function(DocumentRepository $dr) use ($user)
                        {
                            return $dr->createQueryBuilder()
                                    ->field('id')->notEqual($user->getId())
                                    ->sort('username', 'asc');
                        },
            ));
        } else
        {
            $usernames = $this->container->get('doctrine.odm.mongodb.document_manager')
                                    ->getRepository('CelsiusCelsius3Bundle:BaseUser')
                                    ->createQueryBuilder()
                                    ->field('instance.id')->equals($user->getInstance()->getId())
                                    ->field('roles')->in(array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'))
                                    ->getQuery()
                                    ->execute();

            $builder->add('recipients', 'recipients_selector_hidden', array(
                'data' => $usernames,
            ));
        }

        $builder
                ->add('subject', 'text')
                ->add('body', 'textarea');
    }

}
