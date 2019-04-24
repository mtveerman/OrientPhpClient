<?php

declare(strict_types=1);

namespace mtveerman\OrientPhpClient;

use mtveerman\OrientPhpClient\Client;
use mtveerman\OrientPhpClient\Config;
use mtveerman\OrientPhpClient\Protocols\HttpProtocol;

class ClientTest extends BaseTest
{
    protected $config;

    public function setUp():void
    {
        parent::setUp();

        $this->config = new Config();

        $this->config->server = getenv("DB_SERVER");
        $this->config->username = getenv("DB_USERNAME");
        $this->config->password = getenv("DB_PASSWORD");
        $this->config->database = getenv("DB_DATABASE");
    }

    public function testClient()
    {
        $client = new Client(new HttpProtocol());
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConfig()
    {
        $client = new Client(new HttpProtocol(), new Config());
        $this->assertNull($client->config()->name);
        $this->assertEquals("localhost", $client->config->server);

        $new = new Config();
        $new->server = "server.com";
        $client->setConfig($new);
        $this->assertEquals("server.com", $client->config->server);
    }

    public function testPassThruWithAConnectCall()
    {
        $client = new Client(new HttpProtocol(), $this->config);
        $result = $client->connect();
        $this->assertTrue($result);
    }
}
