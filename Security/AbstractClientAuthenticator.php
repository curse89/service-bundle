<?php

namespace RetailCrm\ServiceBundle\Security;

use RetailCrm\ServiceBundle\Models\Error;
use RetailCrm\ServiceBundle\Response\ErrorJsonResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

abstract class AbstractClientAuthenticator extends AbstractAuthenticator
{
    public const AUTH_FIELD = 'clientId';

    private $errorResponseFactory;

    public function __construct(ErrorJsonResponseFactory $errorResponseFactory)
    {
        $this->errorResponseFactory = $errorResponseFactory;
    }

    /**
     * {@inheritdoc }
     */
    abstract public function supports(Request $request): ?bool;

    /**
     * {@inheritdoc }
     */
    abstract public function authenticate(Request $request): Passport;

    /**
     * {@inheritdoc }
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $error = new Error();
        $error->message = $exception->getMessageKey();

        return $this->errorResponseFactory->create($error,Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc }
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }
}
