<?php

namespace Mailxpert\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mailxpert\OAuth2\Client\Provider\Mailxpert;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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
        $json = file_get_contents(\dirname(\dirname(__DIR__)).'/'.$file);
        $data = json_decode($json, true);
        if ($encode && JSON_ERROR_NONE == json_last_error()) {
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
        $this->assertEquals('app.mailxpert.ch', $uri['host']);
        $this->assertEquals('/auth/v3/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl(): void
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('app.mailxpert.ch', $uri['host']);
        $this->assertEquals('/auth/v3/token', $uri['path']);
    }

    public function testGetAccessToken(): void
    {
        $responseJson = $this->getJsonFile('access_token_response.json');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponseStream = $this->createMock(StreamInterface::class);

        $mockResponseStream->method('getContents')->willReturn($responseJson);
        $mockResponse->method('getBody')->willReturn($mockResponseStream);

        $responseData = json_decode($mockResponse->getBody()->getContents(), true);

        $this->assertCount(5, $responseData);
        $this->assertEquals('mock_access_token', $responseData['access_token']);
        $this->assertEquals(3600, $responseData['expires_in']);
        $this->assertEquals('bearer', $responseData['token_type']);
        $this->assertEquals(null, $responseData['scope']);
        $this->assertEquals('mock_refresh_token', $responseData['refresh_token']);
    }

    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $this->expectException(IdentityProviderException::class);

        $message = uniqid();
        $status = rand(400, 600);

        $postResponse = $this->createMock(ResponseInterface::class);
        $postResponseStream = $this->createMock(StreamInterface::class);

        $postResponseStream->method('getContents')->willReturn('{"message": "'.$message.'","code": "invalid","fields": {"first_name": ["Required"]}}');
        $postResponseStream->method('__toString')->willReturn('{"message": "'.$message.'","code": "invalid","fields": {"first_name": ["Required"]}}');
        $postResponse->method('getBody')->willReturn($postResponseStream);
        $postResponse->method('getHeader')->willReturn(['content-type' => 'json']);
        $postResponse->method('getStatusCode')->willReturn($status);

        $client = $this->createMock(\GuzzleHttp\ClientInterface::class);
        $client->method('send')->willReturn($postResponse);

        $this->provider->setHttpClient($client);
        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    public function testGetResourceOwnerDetailsUrl()
    {
        $token = $this->createMock(AccessToken::class);
        $detailsUrl = $this->provider->getResourceOwnerDetailsUrl($token);
        $this->assertEquals('https://api.mailxpert.ch/v3/me', $detailsUrl);
    }

    public function testCreateResourceOwner()
    {
        $token = $this->createMock(AccessToken::class);
        $class = new \ReflectionClass(Mailxpert::class);
        $method = $class->getMethod('createResourceOwner');
        $method->setAccessible(true);
        $resourceOwner = $method->invokeArgs($this->provider, [['uid' => 'customer/user'], $token]);

        $this->assertInstanceOf(GenericResourceOwner::class, $resourceOwner);
        $this->assertEquals('customer/user', $resourceOwner->getId());
        $this->assertEquals('customer/user', $resourceOwner->toArray()['uid']);
    }
}
