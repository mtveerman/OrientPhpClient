<?php

namespace mtveerman\OrientPhpClient\Protocols;

use mtveerman\OrientPhpClient\Protocol;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use mtveerman\OrientPhpClient\Traits\DecodesHttpResponses;

class HttpProtocol extends Protocol
{
    use DecodesHttpResponses;

    protected $connected = false;

    protected $httpclient;

    protected $cookieJar;

    protected $OSessionID;

    public function __construct()
    {
        parent::__construct();
        $this->initiate();
    }

    public function initiate()
    {
        // Create the Guzzle client
        if (!$this->httpclient) {
            $this->cookieJar = new CookieJar();
            $this->httpclient = new HttpClient([
              'cookies'  => $this->cookieJar,
              'headers'  => [
                'Accept-Encoding' => 'gzip,deflate',
                'return-execution-plan' => false
              ],
            ]);
        }
    }

    public function connect()
    {
        if ($this->connected) {
            return true;
        }

        //http://<server>:[<port>]/connect/<database>
        $server = $this->client->config->server;
        $port = $this->client->config->port;
        $database = $this->client->config->database;
        $username = $this->client->config->username;
        $password = $this->client->config->password;
        $timeout = $this->client->config->timeout;

        $url = sprintf('http://%s:%d/connect/%s', $server, $port, $database);

        $result = $this->httpclient->request('GET', $url, ['timeout'=>$timeout, 'auth' => [$username, $password]]);

        if ($result->getStatusCode() == 204) {
            $this->connected = true;

            return true;
        }

        return false;
    }

    public function query($query, $language = "sql")
    {
        $this->connect();

        //http://<server>:[<port>]/query/<database>/<language>/<query-text>[/<limit>][/<fetchPlan>]
        $server = $this->client->config->server;
        $port = $this->client->config->port;
        $database = $this->client->config->database;
        $timeout = $this->client->config->timeout;

        $query = urlencode($query);

        $url = sprintf('http://%s:%d/query/%s/%s/%s', $server, $port, $database, $language, $query);

        $result = $this->httpclient->request('GET', $url, ['timeout'=>$timeout, ]);

        if ($result->getStatusCode() == 200) {
            return $this->decodeResponse($result);
        }

        return false;
    }

    public function command($command, $parameters = [], $language = "sql")
    {
        $this->connect();

        //http://<server>:[<port>]/command/<database>/<language>[/<command-text>[/limit[/<fetchPlan>]]]
        $server = $this->client->config->server;
        $port = $this->client->config->port;
        $database = $this->client->config->database;
        $timeout = $this->client->config->timeout;

        $url = sprintf('http://%s:%d/command/%s/%s//20', $server, $port, $database, $language);

        $body = new \stdClass;
        $body->command = $command;
        $body->parameters = $parameters;
        $body = json_encode($body);

        $result = $this->httpclient->request('POST', $url, ['timeout'=>$timeout, 'body'=>$body]);

        if ($result->getStatusCode() == 200) {
            return $this->decodeResponse($result);
        }

        return false;
    }

    public function batch($command, $parameters = [], $language = "sql", $transaction = false)
    {
        $this->connect();

        //http://<server>:[<port>]/batch/<database>
        $server = $this->client->config->server;
        $port = $this->client->config->port;
        $database = $this->client->config->database;
        $timeout = $this->client->config->timeout;

        $url = sprintf('http://%s:%d/batch/%s', $server, $port, $database);

        $body = new \stdClass;
        $body->transaction = $transaction;
        $body->operations = [];

        // Add a single operation
        $op = new \stdClass;
        $op->type = "script";
        $op->language = $language;
        $op->script = $command;
        if (count($parameters) > 0) {
            $op->parameters = $parameters;
        }

        array_push($body->operations, $op);

        $body = json_encode($body);

        $result = $this->httpclient->request('POST', $url, ['timeout'=>$timeout, 'body'=>$body]);

        if ($result->getStatusCode() == 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }


    public function server()
    {
        $this->connect();

        $server = $this->client->config->server;
        $port = $this->client->config->port;
        $username = $this->client->config->username;
        $password = $this->client->config->password;
        $timeout = $this->client->config->timeout;

        $url = sprintf('http://%s:%d/server', $server, $port);

        $result = $this->httpclient->request('GET', $url, ['timeout'=>$timeout, 'auth' => [$username, $password]]);

        if ($result->getStatusCode() == 200) {
            $json = json_decode($result->getBody()->getContents());
            return $json;
        }

        return false;
    }
}
