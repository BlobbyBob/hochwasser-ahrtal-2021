<?php

namespace BlobbyBob\HochwasserAhrtal2021;

use PDO;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Endpoint
{

    private string $method;
    private string $requestedMethod;
    private string $path;
    private $handler;
    private array $args;

    public function __construct(string $method, string $path, callable $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->args = [];
    }

    public function matches($method, $path): bool
    {
        $this->requestedMethod = $method;
        if ($this->requestedMethod != $this->method && $this->requestedMethod != "HEAD") return false;

        $segments = explode("/", $path);
        $expected = explode("/", $this->path);

        if (count($segments) != count($expected)) return false;

        for ($i = 0; $i < count($expected); $i++) {
            if ($expected[$i][0] == ":") { // Parameters have the form ":param"
                $this->args[substr($expected[$i], 1)] = $segments[$i];
            } else if ($segments[$i] != $expected[$i]) {
                break;
            }
        }

        if ($path == $this->path) return false;
        return true;
    }

    public function execute(Request $request, Response $response, PDO $pdo): array|bool
    {
        // HEAD Requests on POST endpoints should not execute the handler
        if ($this->requestedMethod == "HEAD" && $this->method == "POST") {
            $response->setStatusCode(405, "Method Not Allowed");
            return false;
        }
        return ($this->handler)($request, $response, $pdo, $this->args);
    }
}