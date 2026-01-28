<?php

declare(strict_types=1);

namespace Mailxpert\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mailxpert\OAuth2\Client\Provider\Mailxpert;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MailxpertTest extends TestCase
{
    use QueryBuilderTrait;

    /** @var Mailxpert */
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new Mailxpert([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    protected function getJsonFile($file, $encode = false)
    {
        $json = file_get_contents(\dirname(__DIR__, 2).'/'.$file);
        $data = json_decode($json, true);
        if ($encode && \JSON_ERROR_NONE == json_last_error()) {
            return $data;
        }

        return $json;
    }

    public function testAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('v5.mailxpert.ch', $uri['host']);
        $this->assertEquals('/oauth/v2/auth', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl(): void
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('v5.mailxpert.ch', $uri['host']);
        $this->assertEquals('/oauth/v2/token', $uri['path']);
    }

    public function testGetAccessToken(): void
    {
        $accessToken = $this->getJsonFile('access_token_response.json');
        $stream = m::mock('Psr\Http\Message\StreamInterface');
        $stream->shouldReceive('__toString')->andReturn($accessToken);
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($stream);
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertNotNull($token->getExpires());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testExceptionThrownWhenErrorObjectReceived(): void
    {
        $this->expectException(\League\OAuth2\Client\Provider\Exception\IdentityProviderException::class);
        $message = uniqid();
        $status = random_int(400, 600);
        $stream = m::mock('Psr\Http\Message\StreamInterface');
        $stream->shouldReceive('__toString')->andReturn('{"message": "'.$message.'","code": "invalid","fields": {"first_name": ["Required"]}}');
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn($stream);
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    public function testGetResourceOwnerDetailsUrl(): void
    {
        $this->expectException(\Mailxpert\OAuth2\Client\Exception\ResourceOwnerException::class);
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $this->provider->getResourceOwnerDetailsUrl($token);
    }

    public function testCreateResourceOwner(): void
    {
        $this->expectException(\Mailxpert\OAuth2\Client\Exception\ResourceOwnerException::class);
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $class = new ReflectionClass('Mailxpert\OAuth2\Client\Provider\Mailxpert');
        $method = $class->getMethod('createResourceOwner');
        $method->setAccessible(true);
        $method->invokeArgs($this->provider, [[], $token]);
    }
}
