<?php

namespace App\Security\Authentication\Provider;

use App\Entity\User;
use App\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WsseAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    private int $lifetime;

    /**
     * @param UserProviderInterface $userProvider
     * @param int $lifetime
     */
    public function __construct(UserProviderInterface $userProvider, int $lifetime = 300)
    {
        $this->userProvider = $userProvider;
        $this->lifetime = $lifetime;
    }

    /**
     * @param TokenInterface $token
     *
     * @return WsseUserToken
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByIdentifier($token->getUserIdentifier());

        if ($user instanceof User
            && $this->validateDigest($token, $user->getWsseKey())
        ) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    /**
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken
            && $token->hasAttribute('nonce')
            && $token->hasAttribute('digest')
            && $token->hasAttribute('created');
    }

    /**
     * @param TokenInterface $token
     * @param string $wsseKey
     *
     * @return bool
     */
    private function validateDigest(TokenInterface $token, string $wsseKey): bool
    {
        $nonce = $token->getAttribute('nonce');
        $digest = $token->getAttribute('digest');
        $createdAt = strtotime($token->getAttribute('created'));
        if (!$createdAt) {
            throw new BadCredentialsException('Incorrectly formatted "created" in token.');
        }

        if (abs($createdAt - time()) > $this->lifetime) {
            throw new CredentialsExpiredException('Token has expired.');
        }

        $expected = base64_encode(
            sha1(base64_decode($nonce) . $token->getAttribute('created') . $wsseKey, true)
        );

        return hash_equals($expected, $digest);
    }
}
