<?php

declare(strict_types=1);

namespace Mailxpert\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Mailxpert\OAuth2\Client\Exception\ResourceOwnerException;
use Psr\Http\Message\ResponseInterface;

final class Mailxpert extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $baseHost = 'mailxpert.ch';

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://app.'.$this->baseHost.'/oauth/v2/auth';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://app.'.$this->baseHost.'/oauth/v2/token';
    }

    /**
     * @throws ResourceOwnerException
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): void
    {
        throw new ResourceOwnerException('Not implemented.');
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new IdentityProviderException($data['message'] ?? $response->getReasonPhrase(), $statusCode, $response);
        }
    }

    /**
     * @throws ResourceOwnerException
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        throw new ResourceOwnerException('Not implemented.');
    }

    protected function getPkceMethod(): string
    {
        return self::PKCE_METHOD_S256;
    }
}
