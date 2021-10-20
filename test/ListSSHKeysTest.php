<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/ssh-keys?per_page=100", null, 200, '{
  "ssh_keys": [
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
      "date_created": "2020-10-10T01:56:20+00:00",
      "name": "Example SSH Key",
      "ssh_key": "ssh-rsa AA... user@example.com"
    }
  ],
  "meta": {
    "total": 1,
    "links": {
      "next": "",
      "prev": ""
    }
  }
}')], function(Vultr $vultr) {
    /**
     * @var \Iggyvolz\Vultr\SSHKeys\SshKey[] $keys
     */
    $keys = iterator_to_array($vultr->SSHKeys->getSSHKeys());
    Assert::count(1, $keys);
    Assert::true(array_is_list($keys));
    Assert::same("cb676a46-66fd-4dfb-b839-443f2e6c0b60", $keys[0]->id);
    Assert::same("2020-10-10T01:56:20+00:00", $keys[0]->dateCreated->format(DATE_RFC3339));
    Assert::same("Example SSH Key", $keys[0]->name);
    Assert::same("ssh-rsa AA... user@example.com", $keys[0]->sshKey);
}
);
with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/ssh-keys?per_page=1", null, 200, '{
  "ssh_keys": [
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
      "date_created": "2020-10-10T01:56:20+00:00",
      "name": "Example SSH Key",
      "ssh_key": "ssh-rsa AA... user@example.com"
    }
  ],
  "meta": {
    "total": 2,
    "links": {
      "next": "nextPage",
      "prev": ""
    }
  }
}'),
    new ExpectedRequest(HttpMethod::GET, "/v2/ssh-keys?per_page=1&cursor=nextPage", null, 200, '{
  "ssh_keys": [
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b61",
      "date_created": "2020-10-10T01:56:20+00:00",
      "name": "Example SSH Key",
      "ssh_key": "ssh-rsa AA... user@example.com"
    }
  ],
  "meta": {
    "total": 2,
    "links": {
      "next": "",
      "prev": ""
    }
  }
}')
], function(Vultr $vultr) {
    /**
     * @var \Iggyvolz\Vultr\SSHKeys\SshKey[] $keys
     */
    $keys = iterator_to_array($vultr->SSHKeys->getSSHKeys(1));
    Assert::count(2, $keys);
    Assert::true(array_is_list($keys));
    Assert::same("cb676a46-66fd-4dfb-b839-443f2e6c0b60", $keys[0]->id);
    Assert::same("2020-10-10T01:56:20+00:00", $keys[0]->dateCreated->format(DATE_RFC3339));
    Assert::same("Example SSH Key", $keys[0]->name);
    Assert::same("ssh-rsa AA... user@example.com", $keys[0]->sshKey);
    Assert::same("cb676a46-66fd-4dfb-b839-443f2e6c0b61", $keys[1]->id);
    Assert::same("2020-10-10T01:56:20+00:00", $keys[1]->dateCreated->format(DATE_RFC3339));
    Assert::same("Example SSH Key", $keys[1]->name);
    Assert::same("ssh-rsa AA... user@example.com", $keys[1]->sshKey);
}
);