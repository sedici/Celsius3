<?php

namespace Celsius3\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Security\UserProvider as BaseUserProvider;
use Doctrine\ORM\EntityManager;

class UserProvider extends BaseUserProvider
{

    protected $em;
    protected $request_stack;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager, EntityManager $em, RequestStack $request_stack)
    {
        parent::__construct($userManager);
        $this->em = $em;
        $this->request_stack = $request_stack;
    }

    /**
     * Finds a user by username.
     *
     * This method is meant to be an extension point for child classes.
     *
     * @param string $identifier
     *
     * @return UserInterface|null
     */
    protected function findUser($identifier)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($identifier);
        if ($user) {

        if ($user->getInstance()->getId()){
            return $user;
        }else{
            $instance = $this->em->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array('host' => $user->getInstance()->getHost()));
        }
        if ($instance) {
                if ($user->getInstance()->getHost() === $this->request_stack->getCurrentRequest()->getHost() || array_key_exists($instance->getId(), $user->getSecondaryInstances())) {
                    return $user;
                }
            }
        }

        return null;
    }

}
