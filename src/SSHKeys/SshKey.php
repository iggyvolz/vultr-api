<?php

namespace Iggyvolz\Vultr\SSHKeys;

use Iggyvolz\Vultr\Vultr;

class SshKey
{
    public readonly string $id;
    public readonly \DateTime $dateCreated;
    public readonly string $name;
    public readonly string $sshKey;
    public function __construct(array $data)
    {
        $this->id = Vultr::assertString($data["id"] ?? null);
        $this->dateCreated = Vultr::assertDate($data["date_created"] ?? null);
        $this->name = Vultr::assertString($data["name"] ?? null);
        $this->sshKey = Vultr::assertString($data["ssh_key"] ?? null);

    }
}