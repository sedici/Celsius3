<?php

namespace Celsius3\Security;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use function array_key_exists;

//use FOS\UserBundle\Model\UserManagerInterface;
//use FOS\UserBundle\Security\UserProvider as BaseUserProvider;

/**
 * @method void upgradePassword(PasswordAuthenticatedUserInterface|UserInterface $user, string $newHashedPassword)
 * @method UserInterface loadUserByIdentifier(string $identifier)
 */
class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{

    protected $em;
    protected $request_stack;
    private BaseUserRepositoryInterface $baseUserRepository;

    public function __construct(
        BaseUserRepositoryInterface $baseUserRepository,
        EntityManagerInterface $em,
        RequestStack $request_stack
    ) {
        $this->em = $em;
        $this->request_stack = $request_stack;
        $this->baseUserRepository = $baseUserRepository;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return $class === BaseUser::class;
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->findUser($identifier);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method void upgradePassword(PasswordAuthenticatedUserInterface|UserInterface $user, string $newHashedPassword)
        // TODO: Implement @method UserInterface loadUserByIdentifier(string $identifier)
    }

    private function findUser(string $identifier)
    {
        $user = $this->baseUserRepository->findUserByUsernameOrEmail($identifier);

        if ($user) {
            if ($user->getInstance()->getId()) {
                return $user;
            }

            $instance = $this->em->getRepository(Instance::class)
                ->findOneBy(['host' => $user->getInstance()->getHost()]);
            if ($instance) {
                if (array_key_exists($instance->getId(), $user->getSecondaryInstances())
                    || $user->getInstance()->getHost() === $this->request_stack->getCurrentRequest()->getHost()) {
                    return $user;
                }
            }
        }

        return null;
    }

    public function loadUserByUsername(string $username): ?UserInterface
    {
        return $this->findUser($username);
    }
}
