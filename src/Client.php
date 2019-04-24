<?php

namespace mtveerman\OrientPhpClient;

use Exceptions\WrongProtocolException;

class Client
{
    protected $config;

    protected $protocol;

    public function __construct(Protocol $protocol, Config $config = null)
    {
        $this->setProtocol($protocol);

        if ($config) {
            $this->setConfig($config);
        }
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function setProtocol(Protocol $protocol)
    {
        $this->protocol = $protocol;
        $protocol->setClient($this);
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

        // Else we give the call to the protocol
        return $this->protocol->{$name}(...$arguments);
    }
}
