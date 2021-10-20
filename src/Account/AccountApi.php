<?php

namespace Iggyvolz\Vultr\Account;

use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\UnexpectedResponseException;
use Iggyvolz\Vultr\Vultr;

class AccountApi
{
    public function __construct(private Vultr $vultr)
    {
    }
    public function getAccountInfo(): AccountInfo
    {
        $response = $this->vultr->makeRequest("account", HttpMethod::GET);
        return match($response->responseCode) {
            200 => new AccountInfo($response->response["account"] ?? throw new \RuntimeException()),
            default => throw new UnexpectedResponseException()
        };
    }
}