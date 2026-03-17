<?php

declare(strict_types=1);

namespace App\Security;

use App\DTO\WsseTokenDTO;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use UnexpectedValueException;

class WsseAuthenticator extends AbstractAuthenticator
{
    public function __construct(private int $lifetime)
    {
        $this->lifetime = $lifetime;
    }

    public function authenticate(Request $request): Passport
    {
        $wsseHeader = $request->headers->get('X-WSSE');
        if ($wsseHeader === null) {
            throw new CustomUserMessageAuthenticationException('No X-WSSE header provided');
        }

        $wsseInfo = $this->parseHeader($wsseHeader);

        return new Passport(
            new UserBadge($wsseInfo['username']),
            new CustomCredentials([$this, 'validateDigest'], $wsseInfo)
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-WSSE');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function validateDigest(WsseTokenDTO $token, UserInterface $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        $expected = base64_encode(
            sha1(base64_decode($token->nonce) . $token->created . $user->getWsseKey(), true)
        );

        return hash_equals($expected, $token->digest);
    }

    private function parseHeader(string $header): WsseTokenDTO
    {
        $result = new WsseTokenDTO();

        try {
            $result->username = $this->parseValue('Username', $header);
            $result->digest = $this->parseValue('PasswordDigest', $header);
            $result->nonce = $this->parseValue('Nonce', $header);
            $result->created = $this->parseValue('Created', $header);
        } catch (UnexpectedValueException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid X-WSSE header', previous: $e);
        }

        $createdAt = strtotime($result->created);
        if (!$createdAt) {
            throw new CustomUserMessageAuthenticationException('Incorrectly formatted "created" in X-WSSE header.');
        }

        if (abs($createdAt - time()) > $this->lifetime) {
            throw new CustomUserMessageAuthenticationException('X-WSSE header has expired.');
        }

        return $result;
    }

    private function parseValue(string $key, string $header): string
    {
        if (!preg_match('/' . $key . '="([^"]+)"/', $header, $matches)) {
            throw new UnexpectedValueException("The \"{$key}\" was not found");
        }

        return (string)$matches[1];
    }
}
