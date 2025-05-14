<?php

namespace Mailxpert\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Mailxpert extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $baseHost = 'mailxpert.ch';

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://app.' . $this->baseHost . '/auth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://app.' . $this->baseHost . '/auth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://api.' . $this->baseHost . '/me';
    }

    protected function getDefaultScopes(): array
    {
        return [
            'EMAIL',
            'PROFILE',
        ];
    }

    /** @inheritDoc */
    protected function checkResponse(ResponseInterface $response, $data): void
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

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new GenericResourceOwner($response, 'uid');
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    protected function getPkceMethod(): string
    {
        return self::PKCE_METHOD_S256;
    }
}
