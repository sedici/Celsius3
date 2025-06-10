<?php

namespace Celsius3\Security;

use Celsius3\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\DependencyInjection\Attribute\AsService;


#[AsService]
class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $repository
    ) {}

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $token = $this->repository->findOneByValue($accessToken);

        if (null === $token || !$token->isValid()) {
            throw new BadCredentialsException('Invalid or expired token.');
        }

        // Retorna el UserBadge con el identificador del usuario (email, username, id, etc.)
        return new UserBadge($token->getUser()->getUserIdentifier());
    }
}
