<?php

namespace Celsius\Celsius3MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\MessageBundle\FormType\NewThreadMessageFormType as BaseNewThreadMessageFormType;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;
use FOS\UserBundle\Form\DataTransformer\UserToUsernameTransformer;

class NewThreadMessageFormType extends BaseNewThreadMessageFormType
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->container->get('security.context')->isGranted('ROLE_ADMIN');

        if ($isAdmin)
        {
            $builder
                    ->add('recipient', 'recipients_selector_custom', array(
                        'class' => 'Celsius\\Celsius3Bundle\\Document\\BaseUser',
                        'property' => 'username',
                        'multiple' => true,
            ));
        } else
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $builder
                    ->add('recipient', 'recipients_selector_custom', array(
                        'class' => 'Celsius\\Celsius3Bundle\\Document\\BaseUser',
                        'property' => 'username',
                        'multiple' => true,
                        'query_builder' => function(DocumentRepository $dr) use ($user)
                        {
                            return $dr->createQueryBuilder()
                                    ->field('roles')->in(array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'))
                                    ->field('instance.id')->equals($user->getInstance()->getId())
                                    ->sort('username', 'asc');
                        },
                    ));
        }

        $builder
                ->add('subject', 'text')
                ->add('body', 'textarea');
    }

}
