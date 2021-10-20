<?php

namespace Iggyvolz\Vultr;

use Psr\Http\Message\ResponseInterface;

class VultrResponse
{
    public readonly int $responseCode;
    public readonly ?array $response;
    public function __construct(ResponseInterface $response)
    {
        $this->responseCode = $response->getStatusCode();
        $responseContents = $response->getBody()->getContents();
        try {
            $responseContents = json_decode($responseContents, true, 512, JSON_THROW_ON_ERROR);
            if(is_array($responseContents)) {
                $this->response = $responseContents;
            } else {
                $this->response = null;
            }
        } catch(\JsonException)
        {
            $this->response = null;
        }
    }
}