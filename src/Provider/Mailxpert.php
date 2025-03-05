<?php

namespace Mailxpert\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Mailxpert\OAuth2\Client\Exception\ResourceOwnerException;
use Psr\Http\Message\ResponseInterface;

class Mailxpert extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $baseHost = 'mailxpert.ch';

    /**
     * Get authorization url to begin OAuth 2.0 'Authorization Code' grant.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://app.' . $this->baseHost . '/auth/v3/authorize';
    }

    /**
     * Get access token url to retrieve token.
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://app.' . $this->baseHost . '/auth/v3/token';
    }

    /**
     * We do currently not support an owner resource.
     *
     * @param AccessToken $token
     *
     * @return string|void
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.' . $this->baseHost . '/v3/me';
    }

    /**
     * Get the default scopes uses by this provider.
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return [
            'EMAIL',
            'PROFILE',
        ];
    }

    /**
     * @param ResponseInterface $response
     * @param array|string      $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new IdentityProviderException(
                isset($data['message']) ? $data['message'] : $response->getReasonPhrase(),
                $statusCode,
                $response
            );
        }
    }

    /**
     * @param array       $response
     * @param AccessToken $token
     *
     * @throws ResourceOwnerException
     *
     * @return ResourceOwnerInterface|void
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new GenericResourceOwner($response, 'uid');
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }
}
