<?php

namespace mtveerman\OrientPhpClient\Traits;

trait DecodesHttpResponses
{
    public function decodeResponse($response)
    {
        $json = json_decode($response->getBody()->getContents());
        return $json->result;
    }
}
