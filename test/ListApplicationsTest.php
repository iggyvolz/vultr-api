<?php

use Iggyvolz\Vultr\Applications\Application;
use Iggyvolz\Vultr\Applications\ApplicationType;
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Tester\Assert;
require_once __DIR__ . "/bootstrap.php";
with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/applications?type=all&per_page=100", null, 200, '{
  "applications": [
    {
      "id": 1,
      "name": "LEMP",
      "short_name": "lemp",
      "deploy_name": "LEMP on CentOS 6 x64",
      "type": "one-click",
      "vendor": "vultr",
      "image_id": ""
    },
    {
      "id": 1028,
      "name": "OpenLiteSpeed WordPress",
      "short_name": "openlitespeedwordpress",
      "deploy_name": "OpenLiteSpeed WordPress on Ubuntu 20.04 x64",
      "type": "marketplace",
      "vendor": "LiteSpeed_Technologies",
      "image_id": "openlitespeed-wordpress"
    }
  ],
  "meta": {
    "total": 2,
    "links": {
      "next": "",
      "prev": ""
    }
  }
}'),
], function(Vultr $v): void {
    /**
     * @var Application[] $applications
     */
    $applications = iterator_to_array($v->Applications->getApplications());
    Assert::count(2, $applications);
    Assert::true(array_is_list($applications));
    Assert::same(1, $applications[0]->id);
    Assert::same("LEMP", $applications[0]->name);
    Assert::same("lemp", $applications[0]->shortName);
    Assert::same("LEMP on CentOS 6 x64", $applications[0]->deployName);
    Assert::same(ApplicationType::OneClick, $applications[0]->type);
    Assert::same("vultr", $applications[0]->vendor);
    Assert::same("", $applications[0]->imageId);
    Assert::same(1028, $applications[1]->id);
    Assert::same("OpenLiteSpeed WordPress", $applications[1]->name);
    Assert::same("openlitespeedwordpress", $applications[1]->shortName);
    Assert::same("OpenLiteSpeed WordPress on Ubuntu 20.04 x64", $applications[1]->deployName);
    Assert::same(ApplicationType::Marketplace, $applications[1]->type);
    Assert::same("LiteSpeed_Technologies", $applications[1]->vendor);
    Assert::same("openlitespeed-wordpress", $applications[1]->imageId);
});

with_vultr([
    new ExpectedRequest(HttpMethod::GET, "/v2/applications?type=all&per_page=100", null, 200, '{
  "applications": [
    {
      "id": 1,
      "name": "LEMP",
      "short_name": "lemp",
      "deploy_name": "LEMP on CentOS 6 x64",
      "type": "one-click",
      "vendor": "vultr",
      "image_id": ""
    }
  ],
  "meta": {
    "total": 2,
    "links": {
      "next": "nextpage",
      "prev": ""
    }
  }
}'),
    new ExpectedRequest(HttpMethod::GET, "/v2/applications?type=all&per_page=100&cursor=nextpage", null, 200, '{
  "applications": [
    {
      "id": 1028,
      "name": "OpenLiteSpeed WordPress",
      "short_name": "openlitespeedwordpress",
      "deploy_name": "OpenLiteSpeed WordPress on Ubuntu 20.04 x64",
      "type": "marketplace",
      "vendor": "LiteSpeed_Technologies",
      "image_id": "openlitespeed-wordpress"
    }
  ],
  "meta": {
    "total": 2,
    "links": {
      "next": "",
      "prev": ""
    }
  }
}'),
], function(Vultr $v): void {
    /**
     * @var Application[] $applications
     */
    $applications = iterator_to_array($v->Applications->getApplications());
    Assert::count(2, $applications);
    Assert::true(array_is_list($applications));
    Assert::same(1, $applications[0]->id);
    Assert::same("LEMP", $applications[0]->name);
    Assert::same("lemp", $applications[0]->shortName);
    Assert::same("LEMP on CentOS 6 x64", $applications[0]->deployName);
    Assert::same(ApplicationType::OneClick, $applications[0]->type);
    Assert::same("vultr", $applications[0]->vendor);
    Assert::same("", $applications[0]->imageId);
    Assert::same(1028, $applications[1]->id);
    Assert::same("OpenLiteSpeed WordPress", $applications[1]->name);
    Assert::same("openlitespeedwordpress", $applications[1]->shortName);
    Assert::same("OpenLiteSpeed WordPress on Ubuntu 20.04 x64", $applications[1]->deployName);
    Assert::same(ApplicationType::Marketplace, $applications[1]->type);
    Assert::same("LiteSpeed_Technologies", $applications[1]->vendor);
    Assert::same("openlitespeed-wordpress", $applications[1]->imageId);
});
