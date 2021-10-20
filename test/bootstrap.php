<?php
use Iggyvolz\Vultr\HttpMethod;
use Iggyvolz\Vultr\Vultr;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\AbstractLogger;
use Tester\Assert;
use Tester\Environment;
require_once __DIR__ . "/../vendor/autoload.php";

Environment::setup();

class ExpectedRequest implements Stringable
{
    private bool $fulfilled = false;
    public function __construct(
        private readonly HttpMethod $method,
        private readonly string $uri,
        private readonly ?string $requestBody,
        public readonly int $responseCode,
        public readonly ?string $responseBody,
    ) {}
    public function attempt(HttpMethod $method, string $uri, ?string $requestBody): bool
    {
        if($this->fulfilled) {
            return false;
        }
        if($this->method === $method && $this->uri === $uri && json_decode($this->requestBody ?? "null", associative: true) === json_decode($requestBody ?? "null", associative: true)) {
            $this->fulfilled = true;
            return true;
        }
        return false;
    }

    public function isFulfilled(): bool
    {
        return $this->fulfilled;
    }

    public function __toString(): string
    {
        return $this->method->value . " " . $this->uri . " " . ($this->requestBody ?? "");
    }
}

class MockClient implements ClientInterface
{
    /**
     * @var ExpectedRequest[]
     */
    private array $expectedRequests;

    public function __construct(ExpectedRequest ...$expectedRequests)
    {
        $this->expectedRequests = $expectedRequests;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath() . (empty($request->getUri()->getQuery()) ? "" : ("?" . $request->getUri()->getQuery()));
        $request->getBody()->rewind();
        $requestBody = $request->getBody()->getContents();
        foreach($this->expectedRequests as $expectedRequest) {
            if($expectedRequest->attempt(HttpMethod::from($request->getMethod()), $uri, $requestBody)) {
                $resp = new Response($expectedRequest->responseCode, body: is_null($expectedRequest->responseBody) ? '' : $expectedRequest->responseBody);
                $resp->getBody()->rewind();
                return $resp;
            }
        }
        throw new RuntimeException("Unexpected request ". $request->getMethod() ." $uri " . ($requestBody ?? "") . " (could handle: " . implode($this->expectedRequests) . ")");
    }

    public function assertFulfilled(): void
    {
        foreach($this->expectedRequests as $expectedRequest) {
            Assert::true($expectedRequest->isFulfilled(), "Request $expectedRequest was not fulfilled");
        }
    }
}

function with_vultr(
    array $expectedRequests,
    Closure $callback
) {
    $vultr = new Vultr("", client: $client = new MockClient(
        ...$expectedRequests
    ),
        logger: new class extends AbstractLogger{

            public function log($level, \Stringable|string $message, array $context = []): void
            {
                echo "$message" . PHP_EOL;
            }
        }
    );
    $callback($vultr);
    $client->assertFulfilled();
}