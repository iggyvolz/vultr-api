<?php

namespace Iggyvolz\Vultr\Applications;

enum ApplicationType: string
{
    case Marketplace = "marketplace";
    case OneClick = "one-click";
}