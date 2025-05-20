<?php
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class LoginTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $this->app = $GLOBALS['app'];
    }

    public function testLoginWithValidCredentials()
    {
        $requestFactory = new ServerRequestFactory();
        $streamFactory = new \Slim\Psr7\Factory\StreamFactory();

        $body = $streamFactory->createStream(json_encode([
            'email' => 'user@example.com',
            'password' => 'test1234'
        ]));

        $request = $requestFactory->createServerRequest('POST', '/login')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('token', (string) $response->getBody());
    }

}
