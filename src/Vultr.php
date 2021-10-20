<?php

namespace Iggyvolz\Vultr;

use Iggyvolz\Vultr\Account\AccountApi;
use Iggyvolz\Vultr\Applications\ApplicationsApi;
use Iggyvolz\Vultr\SSHKeys\SSHKeysApi;
use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Vultr
{
    public readonly AccountApi $Account;
    public readonly ApplicationsApi $Applications;
    public readonly SSHKeysApi $SSHKeys;

    public function __construct(
        private string $apiKey,
        private ClientInterface $client,
        private string $apiGateway = "https://api.vultr.com/v2/",
        private LoggerInterface $logger = new NullLogger(),
    )
    {
        $this->Account = new AccountApi($this);
        $this->Applications = new ApplicationsApi($this);
        $this->SSHKeys = new SSHKeysApi($this);
    }

    public static function assertString(mixed $param): string
    {
        if(!is_string($param)) {
            throw new UnexpectedResponseException();
        }
        return $param;
    }

    /**
     * @return list<string>
     */
    public static function assertStringList(mixed $param): array
    {
        if(!is_array($param)) {
            throw new UnexpectedResponseException();
        }
        if(!array_is_list($param)) {
            throw new UnexpectedResponseException();
        }
        return $param;
    }

    public static function assertInt(mixed $param): int
    {
        if(!is_int($param)) {
            throw new UnexpectedResponseException();
        }
        return $param;
    }

    public static function assertNumber(mixed $param): int|float
    {
        if(!is_int($param) && !is_float($param)) {
            throw new UnexpectedResponseException();
        }
        return $param;
    }

    public static function assertDate(mixed $param): \DateTime
    {
        $param = self::assertString($param);
        $result = \DateTime::createFromFormat(\DateTimeInterface::RFC3339, $param);
        if($result === false) {
            throw new UnexpectedResponseException();
        }
        return $result;
    }

    public static function assertArray(mixed $param): array
    {
        if(!is_array($param)) {
            throw new UnexpectedResponseException();
        }
        return $param;
    }

    public function makeRequest(
        string $uri,
        HttpMethod $method,
        ?array $body = null,
    ): VultrResponse {
        $this->logger->info($method->value . " " . $this->apiGateway . $uri);
        return new VultrResponse($this->client->sendRequest(new Request($method->value, $this->apiGateway . $uri, [
            "Authorization" => "Bearer " . $this->apiKey
        ], is_null($body) ? null : json_encode($body, flags: JSON_THROW_ON_ERROR))));
    }
}