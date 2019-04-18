<?php

namespace mtveerman\OrientPhpClient;

abstract class Protocol
{
    protected $client;

    abstract public function connect();

    abstract public function query($query, $language);

    abstract public function command($command, $parameters, $language);

    abstract public function server();

    public function __construct(Client $client = null)
    {
        if ($client) {
            $this->setClient($client);
        }
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}
