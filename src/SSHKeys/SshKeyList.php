<?php

namespace Iggyvolz\Vultr\SSHKeys;

use Iggyvolz\Vultr\Paginator;
use Iggyvolz\Vultr\Vultr;

class SshKeyList extends Paginator
{

    protected function handleData(array $response): iterable
    {
        $data = Vultr::assertArray($response["ssh_keys"] ?? null);
        foreach($data as $entry) {
            $entry = Vultr::assertArray($entry);
            yield new SshKey($entry);
        }
    }
}