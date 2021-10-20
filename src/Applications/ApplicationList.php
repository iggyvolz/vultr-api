<?php

namespace Iggyvolz\Vultr\Applications;

use Iggyvolz\Vultr\Paginator;
use Iggyvolz\Vultr\Vultr;

class ApplicationList extends Paginator
{
    protected function handleData(array $response): iterable
    {
        $data = Vultr::assertArray($response["applications"] ?? null);
        foreach($data as $entry) {
            $entry = Vultr::assertArray($entry);
            yield new Application($entry);
        }
    }
}