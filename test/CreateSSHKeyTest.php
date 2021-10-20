<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::POST, "/v2/ssh-keys", '{

    "name": "Example SSH Key",
    "ssh_key": "ssh-rsa AA... user@example.com"

}', 201, '{

    "ssh_key": 

    {
        "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
        "date_created": "2020-10-10T01:56:20+00:00",
        "name": "Example SSH Key",
        "ssh_key": "ssh-rsa AA... user@example.com"
    }

}')], function(Vultr $vultr) {
    $key = $vultr->SSHKeys->createSSHKey("Example SSH Key", "ssh-rsa AA... user@example.com");
    Assert::same("cb676a46-66fd-4dfb-b839-443f2e6c0b60", $key->id);
    Assert::same("2020-10-10T01:56:20+00:00", $key->dateCreated->format(DATE_RFC3339));
    Assert::same("Example SSH Key", $key->name);
    Assert::same("ssh-rsa AA... user@example.com", $key->sshKey);
}
);