<?php

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
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://v5.mailxpert.ch/oauth/v2/auth';
    }

    /**
     * Get access token url to retrieve token.
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://v5.mailxpert.ch/oauth/v2/token';
    }

    /**
     * We do currently not support an owner resource.
     *
     * @param AccessToken $token
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
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
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
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface|void
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        throw new ResourceOwnerException();
    }
}
