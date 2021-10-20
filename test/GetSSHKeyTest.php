<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/ssh-keys/3b8066a7-b438-455a-9688-44afc9a3597f", null, 200, '{

    "ssh_key": 

    {
        "id": "3b8066a7-b438-455a-9688-44afc9a3597f",
        "date_created": "2020-10-10T01:56:20+00:00",
        "name": "Example SSH Key",
        "ssh_key": "ssh-rsa AA... user@example.com"
    }

}')], function(Vultr $vultr) {
    $key = $vultr->SSHKeys->getSSHKey("3b8066a7-b438-455a-9688-44afc9a3597f");
    Assert::same("3b8066a7-b438-455a-9688-44afc9a3597f", $key->id);
    Assert::same("2020-10-10T01:56:20+00:00", $key->dateCreated->format(DATE_RFC3339));
    Assert::same("Example SSH Key", $key->name);
    Assert::same("ssh-rsa AA... user@example.com", $key->sshKey);
}
);