<?php

namespace Iggyvolz\Vultr\SSHKeys;

use Iggyvolz\Vultr\Account\AccountInfo;
use Iggyvolz\Vultr\Applications\ApplicationList;
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
            201 => new SshKey($response->response["ssh_key"] ?? throw new \RuntimeException()),
            default => throw new UnexpectedResponseException()
        };
    }

    public function updateSSHKey(SshKey|string $key, string $name, string $sshKey): void
    {
        if(!is_string($key)) {
            $key = $key->id;
        }
        if($this->vultr->makeRequest("ssh-keys/$key", HttpMethod::PATCH, ["name" => $name, "ssh_key" => $sshKey])->responseCode !== 204) {
            throw new UnexpectedResponseException();
        }
    }

    public function getSSHKey(string $id): SshKey
    {
        $response = $this->vultr->makeRequest("ssh-keys/$id", HttpMethod::GET);
        return match($response->responseCode) {
            200 => new SshKey($response->response["ssh_key"] ?? throw new \RuntimeException()),
            default => throw new UnexpectedResponseException()
        };
    }

    public function getSSHKeys(int $perPage = 100): SshKeyList
    {
        return new SshKeyList(
            $this->vultr,
            "ssh-keys",
            [
                "per_page" => $perPage
            ]
        );
    }

    public function deleteSSHKey(SshKey|string $key): void
    {
        if(!is_string($key)) {
            $key = $key->id;
        }
        if($this->vultr->makeRequest("ssh-keys/$key", HttpMethod::DELETE)->responseCode !== 204) {
            throw new UnexpectedResponseException();
        }
    }
}