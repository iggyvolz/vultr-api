<?php

namespace Iggyvolz\Vultr\Applications;

use Iggyvolz\Vultr\UnexpectedResponseException;
use Iggyvolz\Vultr\Vultr;

class Application
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $shortName;
    public readonly string $deployName;
    public readonly ApplicationType $type;
    public readonly string $vendor;
    public readonly string $imageId;
    public function __construct(array $data)
    {
        $this->id = Vultr::assertInt($data["id"] ?? null);
        $this->name = Vultr::assertString($data["name"] ?? null);
        $this->shortName = Vultr::assertString($data["short_name"] ?? null);
        $this->deployName = Vultr::assertString($data["deploy_name"] ?? null);
        $this->type = ApplicationType::tryFrom(Vultr::assertString($data["type"] ?? null)) ?? throw new UnexpectedResponseException();
        $this->vendor = Vultr::assertString($data["vendor"] ?? null);
        $this->imageId = Vultr::assertString($data["image_id"] ?? null);
    }
}