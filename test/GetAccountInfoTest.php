<?php

use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/account", null, 200, '{
  "account": {
    "name": "Example Account",
    "email": "admin@example.com",
    "acls": [
      "manage_users",
      "subscriptions_view",
      "subscriptions",
      "billing",
      "support",
      "provisioning",
      "dns",
      "abuse",
      "upgrade",
      "firewall",
      "alerts",
      "objstore",
      "loadbalancer"
    ],
    "balance": -100,
    "pending_charges": 60,
    "last_payment_date": "2020-10-10T01:56:20+00:00",
    "last_payment_amount": -1
  }
}'),
], function(Vultr $v): void {
    $accountInfo = $v->Account->getAccountInfo();
    Assert::same("Example Account", $accountInfo->name);
    Assert::same("admin@example.com", $accountInfo->email);
    Assert::same([
        "manage_users",
        "subscriptions_view",
        "subscriptions",
        "billing",
        "support",
        "provisioning",
        "dns",
        "abuse",
        "upgrade",
        "firewall",
        "alerts",
        "objstore",
        "loadbalancer"
    ], $accountInfo->acls);
    Assert::same(-100, $accountInfo->balance);
    Assert::same(60, $accountInfo->pendingCharges);
    Assert::same("2020-10-10T01:56:20+00:00", $accountInfo->lastPaymentDate->format(DATE_RFC3339));
    Assert::same(-1, $accountInfo->lastPaymentAmount);

});
//$applications = $vultr->Applications->getApplications(perPage: 50);
//var_dump(iterator_to_array($applications));