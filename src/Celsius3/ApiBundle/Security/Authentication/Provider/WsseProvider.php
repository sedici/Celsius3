<?php

namespace Celsius3\ApiBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Celsius3\ApiBundle\Security\InstanceProvider;
use Celsius3\ApiBundle\Security\Authentication\Token\WsseInstanceToken;

class WsseProvider implements AuthenticationProviderInterface
{

    private $instanceProvider;
    private $cacheDir;

    public function __construct(InstanceProvider $instanceProvider, $cacheDir)
    {
        $this->instanceProvider = $instanceProvider;
        $this->cacheDir = $cacheDir;
    }

    public function authenticate(TokenInterface $token)
    {
        $instance = $this->instanceProvider->loadByUrl($token->getUsername());

        if ($instance && $this->validateDigest($token->digest, $token->nonce, $token->created, $instance->get('api_key')->getValue())) {
            $authenticatedToken = new WsseInstanceToken(array('ROLE_INSTANCE'));
            $authenticatedToken->setUser($instance);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Check created time is not in the future
        if (strtotime($created) > time()) {
            return false;
        }

        // Expire timestamp after 5 minutes
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Validate nonce is unique within 5 minutes
        if (file_exists($this->cacheDir . '/' . $nonce) && file_get_contents($this->cacheDir . '/' . $nonce) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }
        // If cache directory does not exist we create it
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        file_put_contents($this->cacheDir . '/' . $nonce, time());

        // Validate Secret
        $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));

        return $digest === $expected;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseInstanceToken;
    }

}