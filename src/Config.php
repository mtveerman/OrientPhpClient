<?php

namespace mtveerman\OrientPhpClient;

class Config
{
    private $server = "localhost";
    private $port = 2480;
    private $database;
    private $username;
    private $password;

    public function __get(string $name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

    public function __set(string $name, $value)
    {
        $this->{$name} = $value;
    }
}
