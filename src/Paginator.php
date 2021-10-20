<?php

namespace Iggyvolz\Vultr;

abstract class Paginator implements \Iterator
{
    private array $entries;
    private ?string $nextCursor;
    private int $i;
    public function __construct(
        private Vultr $vultr,
        private string $uri,
        private ?array $query = null
    )
    {
    }

    protected abstract function handleData(array $response): iterable;

    private function executeRequest(): void
    {
        $query = $this->query;
        if(!is_null($this->nextCursor)) {
            $query["cursor"] = $this->nextCursor;
        }
        $response = $this->vultr->makeRequest($this->uri . "?" . http_build_query($query), HttpMethod::GET);
        if($response->responseCode !== 200 || is_null($response->response)) {
            throw new UnexpectedResponseException();
        }
        foreach($this->handleData($response->response) as $entry) {
            $this->entries[] = $entry;
        }
        $meta = Vultr::assertArray($response->response["meta"] ?? null);
        $next = Vultr::assertString(Vultr::assertArray($meta["links"] ?? null)["next"] ?? null);
        if($next === "") {
            $this->nextCursor = null;
        } else {
            $this->nextCursor = $next;
        }
    }

    public function current(): mixed
    {
        return $this->entries[$this->i] ?? null;
    }

    public function next(): void
    {
        $this->i++;
        if(!$this->valid() && !is_null($this->nextCursor)) {
            $this->executeRequest();
        }
    }

    public function key(): int
    {
        return $this->i;
    }

    public function valid(): bool
    {
        return !is_null($this->current());
    }

    public function rewind(): void
    {
        $this->entries = [];
        $this->nextCursor = null;
        $this->i = 0;
        $this->executeRequest();
    }
}