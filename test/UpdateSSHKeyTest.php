<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::PATCH, "/v2/ssh-keys/ssh-key-id", '{

    "name": "Example SSH Key",
    "ssh_key": "ssh-rsa AA... user@example.com"

}', 204, null)], function(Vultr $vultr) {
    $vultr->SSHKeys->updateSSHKey("ssh-key-id", "Example SSH Key", "ssh-rsa AA... user@example.com");
}
);