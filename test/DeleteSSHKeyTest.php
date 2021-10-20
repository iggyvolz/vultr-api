<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::DELETE, "/v2/ssh-keys/3b8066a7-b438-455a-9688-44afc9a3597f", null, 204, null)], function(Vultr $vultr) {
    $vultr->SSHKeys->deleteSSHKey("3b8066a7-b438-455a-9688-44afc9a3597f");
}
);