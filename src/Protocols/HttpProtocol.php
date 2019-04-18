<?php

namespace mtveerman\OrientPhpClient\Protocols;

use mtveerman\OrientPhpClient\Protocol;

class HttpProtocol extends Protocol
{
    public function connect()
    {
        //http://<server>:[<port>]/connect/<database>
    }
}
