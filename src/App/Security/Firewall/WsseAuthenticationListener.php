<?php

namespace App\Security\Firewall;

use App\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\AbstractListener;
use UnexpectedValueException;

class WsseAuthenticationListener extends AbstractListener
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-WSSE');
    }

    public function authenticate(RequestEvent $event)
    {
        $request = $event->getRequest();

        $wsseInfo = $this->parseHeader($request->headers->get('X-WSSE'));
        if (!$wsseInfo) {
            return;
        }

        $token = new WsseUserToken();
        $token->setUser($wsseInfo['username']);
        $token->setAttribute('digest', $wsseInfo['digest']);
        $token->setAttribute('nonce', $wsseInfo['nonce']);
        $token->setAttribute('created', $wsseInfo['created']);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            $token = $this->tokenStorage->getToken();
            if ($token instanceof WsseUserToken) {
                $this->tokenStorage->setToken(null);
            }
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }

    /**
     * @param string $header
     *
     * @return array|null
     */
    private function parseHeader(string $header): ?array
    {
        $result = [];

        try {
            $result['username'] = $this->parseValue('Username', $header);
            $result['digest'] = $this->parseValue('PasswordDigest', $header);
            $result['nonce'] = $this->parseValue('Nonce', $header);
            $result['created'] = $this->parseValue('Created', $header);
        } catch (UnexpectedValueException $e) {
            return null;
        }

        return $result;
    }

    /**
     * @param string $key
     * @param string $header
     *
     * @return string
     */
    private function parseValue(string $key, string $header): string
    {
        if (!preg_match('/' . $key . '="([^"]+)"/', $header, $matches)) {
            throw new UnexpectedValueException('The string was not found');
        }

        return (string)$matches[1];
    }
}
