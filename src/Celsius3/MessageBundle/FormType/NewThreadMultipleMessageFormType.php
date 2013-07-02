<?php

namespace Celsius3\MessageBundle\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\MessageBundle\FormType\NewThreadMultipleMessageFormType as BaseNewThreadMultipleMessageFormType;
use Celsius3\CoreBundle\Manager\UserManager;

class NewThreadMultipleMessageFormType extends
        BaseNewThreadMultipleMessageFormType
{

    private $context;
    private $dm;

    public function __construct(SecurityContextInterface $context,
            DocumentManager $dm)
    {
        $this->context = $context;
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->context->isGranted('ROLE_ADMIN');
        $user = $this->context->getToken()->getUser();
        if ($isAdmin) {
            $builder
                    ->add('recipients',
                            'celsius3_messagebundle_recipients_selector_custom',
                            array(
                                    'class' => 'Celsius3\\CoreBundle\\Document\\BaseUser',
                                    'property' => 'username',
                                    'multiple' => true,
                                    'query_builder' => function (
                                            DocumentRepository $dr) use ($user)
                                    {
                                        return $dr->createQueryBuilder()
                                                ->field('id')
                                                ->notEqual($user->getId())
                                                ->sort('username', 'asc');
                                    },));
        } else {
            $usernames = $this->dm
                    ->getRepository('Celsius3CoreBundle:BaseUser')
                    ->createQueryBuilder()->field('id')
                    ->notEqual($user->getId())->field('instance.id')
                    ->equals($user->getInstance()->getId())->field('roles')
                    ->in(array(UserManager::ROLE_ADMIN))->getQuery()->execute();

            $builder
                    ->add('recipients', 'celsius3_messagebundle_recipients_selector_hidden',
                            array('data' => $usernames,));
        }

        $builder->add('subject', 'text')
                ->add('body', 'textarea',
                        array('attr' => array('class' => 'tinymce',),));
    }

}
