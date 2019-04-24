<?php

declare(strict_types=1);

namespace mtveerman\OrientPhpClient;

use mtveerman\OrientPhpClient\Client;
use mtveerman\OrientPhpClient\Config;
use mtveerman\OrientPhpClient\Protocols\HttpProtocol;

class HttpProtocolTest extends BaseTest
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

    public function tearDown():void
    {
        $client = new Client(new HttpProtocol(), $this->config);
        $result = $client->command("DELETE VERTEX V");
    }

    public function testHttpProtocol()
    {
        $proto = new HttpProtocol();
        $this->assertInstanceOf(HttpProtocol::class, $proto);
    }

    public function testConnect()
    {
        $client = new Client(new HttpProtocol(), $this->config);
        $result = $client->connect();
        $this->assertTrue($result);
    }

    public function testQuery()
    {
        $client = new Client(new HttpProtocol(), $this->config);
        $result = $client->query("SELECT @rid FROM V");

        $this->assertEquals(0, count($result));

        // Now add something
        $result = $client->command("CREATE VERTEX V SET name='maarten'");

        $this->assertGreaterThan(0, count($result));

        // Run query again
        $result = $client->query("SELECT @rid FROM V");

        $this->assertEquals(1, count($result));

        $this->assertNotEmpty($result[0]->{'@rid'});
    }

    public function testCommand()
    {
        $client = new Client(new HttpProtocol(), $this->config);

        // Now add something
        $result = $client->command("CREATE VERTEX V SET name='maarten'");

        $result = $client->command("SELECT @rid FROM V");

        $this->assertGreaterThan(0, count($result));

        $this->assertNotEmpty($result[0]->{'@rid'});
    }

    public function testCommandWithParameters()
    {
        $client = new Client(new HttpProtocol(), $this->config);

        // Now add something
        $result = $client->command("CREATE VERTEX V SET name=?", ['maarten veerman']);

        $result = $client->command("SELECT @rid, name FROM V");

        $this->assertGreaterThan(0, count($result));

        $this->assertNotEmpty($result[0]->{'@rid'});
        $this->assertEquals("maarten veerman", $result[0]->name);
    }

    public function testCommandWithNamedParameters()
    {
        $client = new Client(new HttpProtocol(), $this->config);

        // Now add something
        $result = $client->command("CREATE VERTEX V SET name=:name, email=:email", ['email'=>'test@example.com', 'name'=>'maarten veerman']);

        $result = $client->command("SELECT @rid, name, email FROM V");

        $this->assertGreaterThan(0, count($result));

        $this->assertNotEmpty($result[0]->{'@rid'});
        $this->assertEquals("maarten veerman", $result[0]->name);
        $this->assertEquals("test@example.com", $result[0]->email);
    }

    public function testServer()
    {
        $client = new Client(new HttpProtocol(), $this->config);
        $result = $client->server();

        $this->assertGreaterThan(0, $result->storages);
    }
}
