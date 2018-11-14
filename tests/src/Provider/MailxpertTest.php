<?php

namespace Mailxpert\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mailxpert\OAuth2\Client\Provider\Mailxpert;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class MailxpertTest extends TestCase
{
    use QueryBuilderTrait;

    /** @var Mailxpert */
    protected $provider;

    protected function setUp()
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

    public function testAuthorizationUrl()
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

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('v5.mailxpert.ch', $uri['host']);
        $this->assertEquals('/oauth/v2/auth', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('v5.mailxpert.ch', $uri['host']);
        $this->assertEquals('/oauth/v2/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $accessToken = $this->getJsonFile('access_token_response.json');
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($accessToken);
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

    /**
     * @expectedException \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     **/
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $message = uniqid();
        $status = rand(400, 600);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('{"message": "'.$message.'","code": "invalid","fields": {"first_name": ["Required"]}}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     * @expectedException \Mailxpert\OAuth2\Client\Exception\ResourceOwnerException
     **/
    public function testGetResourceOwnerDetailsUrl()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $this->provider->getResourceOwnerDetailsUrl($token);
    }

    /**
     * @expectedException \Mailxpert\OAuth2\Client\Exception\ResourceOwnerException
     **/
    public function testCreateResourceOwner()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $class = new \ReflectionClass('Mailxpert\OAuth2\Client\Provider\Mailxpert');
        $method = $class->getMethod('createResourceOwner');
        $method->setAccessible(true);
        $method->invokeArgs($this->provider, [[], $token]);
    }
}
