<?php

declare(strict_types=1);

namespace Mailxpert\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Mailxpert\OAuth2\Client\Exception\ResourceOwnerException;
use Psr\Http\Message\ResponseInterface;

class Mailxpert extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Get authorization url to begin OAuth 2.0 'Authorization Code' grant.
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://v5.mailxpert.ch/oauth/v2/auth';
    }

    /**
     * Get access token url to retrieve token.
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://v5.mailxpert.ch/oauth/v2/token';
    }

    /**
     * We do currently not support an owner resource.
     *
     * @throws ResourceOwnerException
     *
     * @return string|void
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        throw new ResourceOwnerException();
    }

    /**
     * Get the default scopes uses by this provider. Currently there is no support of scopes at all thus returning an empty array.
     */
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * @param array|string $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new IdentityProviderException($data['message'] ?? $response->getReasonPhrase(), $statusCode, $response);
        }
    }

    /**
     * @throws ResourceOwnerException
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface|void
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        throw new ResourceOwnerException();
    }
}
