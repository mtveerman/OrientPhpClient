<?php

declare(strict_types=1);

namespace mtveerman\OrientPhpClient;

use mtveerman\OrientPhpClient\Client;
use mtveerman\OrientPhpClient\Config;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testClient()
    {
        $client = new Client();
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConfig()
    {
        $client = new Client(new Config());
        $this->assertNull($client->config()->name);
        $this->assertEquals("localhost", $client->config->server);

        $new = new Config();
        $new->server = "server.com";
        $client->setConfig($new);
        $this->assertEquals("server.com", $client->config->server);
    }
}
