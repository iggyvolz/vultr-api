<?php

namespace Iggyvolz\Vultr\SSHKeys;

use Iggyvolz\Vultr\Account\AccountInfo;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\UnexpectedResponseException;
use Iggyvolz\Vultr\Vultr;

class SSHKeysApi
{
    public function __construct(private Vultr $vultr)
    {
    }

    public function createSSHKey(string $name, string $sshKey): SshKey
    {
        $response = $this->vultr->makeRequest("ssh-keys", HttpMethod::POST, ["name" => $name, "ssh_key" => $sshKey]);
        return match($response->responseCode) {
            201 => new SshKey($response->response ?? throw new \RuntimeException()),
            default => throw new UnexpectedResponseException()
        };
    }
}