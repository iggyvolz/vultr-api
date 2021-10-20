<?php

namespace Iggyvolz\Vultr\Applications;

use http\QueryString;
use Iggyvolz\Vultr\Account\AccountInfo;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\UnexpectedResponseException;
use Iggyvolz\Vultr\Vultr;

class ApplicationsApi
{
    public function __construct(private Vultr $vultr)
    {
    }
    public function getApplications(?ApplicationType $type = null, $perPage = 100): ApplicationList
    {
        return new ApplicationList(
            $this->vultr,
            "applications",
            [
                "type" => is_null($type) ? "all" : $type->value,
                "per_page" => $perPage
            ]
        );
    }
}