<?php

declare(strict_types=1);

namespace mtveerman\OrientPhpClient;

use mtveerman\OrientPhpClient\Client;
use mtveerman\OrientPhpClient\Config;
use mtveerman\OrientPhpClient\Protocols\HttpProtocol;
use Dotenv;

class BaseTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $dotenv = Dotenv\Dotenv::create(__DIR__ . "\..");
        $dotenv->load();
    }
}
