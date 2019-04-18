<?php

namespace mtveerman\OrientPhpClient;

use Exceptions\WrongProtocolException;

class Client
{
    protected $config;

    const HTTP_PROTOCOL = 1;
    const BINARY_PROTOCOL = 2;

    protected $protocol = self::HTTP_PROTOCOL;

    public function __construct(Config $config = null)
    {
        if ($config) {
            $this->setConfig($config);
        }
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function setProtocolType($type)
    {
        if ($type == $this.HTTP_PROTOCOL) {
            $this->protocol = $type;
        } elseif ($type == $this.BINARYPROTOCOL) {
            throw new WrongProtocolException("Binary protocol not supported");
        } else {
            throw new WrongProtocolException("Wrong protocol");
        }
    }

    public function getProtocolType()
    {
        return $this->protocol;
    }

    public function __get(string $name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
    }

    public function __call(string $name, array $arguments)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
    }
}
