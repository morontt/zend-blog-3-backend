<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 07.04.26
 * Time: 22:57
 */

namespace App\Security;

use App\LogTrait;
use App\Security\SecureCookie\Cookie;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class SecureCookieAuthenticator extends AbstractAuthenticator
{
    use LogTrait;

    public function __construct(
        private Cookie $cookie,
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    public function authenticate(Request $request): Passport
    {
        $sessionData = $request->cookies->get(Cookie::SESSION_KEY);
        if ($sessionData === null) {
            $error = 'No "' . Cookie::SESSION_KEY . '" cookie provided';
            $this->error($error);

            throw new CustomUserMessageAuthenticationException($error);
        }

        $sessionDTO = $this->cookie->decode($sessionData);
        if (!$sessionDTO || empty($sessionDTO->userName)) {
            $error = 'Invalid "' . Cookie::SESSION_KEY . '" cookie data';
            $this->error($error);

            throw new CustomUserMessageAuthenticationException($error);
        }

        if ($sessionDTO->deadLine && $sessionDTO->deadLine < (new DateTime())) {
            $error = 'Session expired';
            $this->error($error);

            throw new CustomUserMessageAuthenticationException($error);
        }

        return new SelfValidatingPassport(new UserBadge($sessionDTO->userName));
    }

    public function supports(Request $request): ?bool
    {
        return $request->cookies->has(Cookie::SESSION_KEY);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }
}
