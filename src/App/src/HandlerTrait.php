<?php

declare(strict_types=1);

namespace App;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Trait for Handler usage
 */
trait HandlerTrait
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match ($request->getMethod()) {
            Http::METHOD_GET  => $this->handleGet($request),
            Http::METHOD_POST => $this->handlePost($request),
            Http::METHOD_DELETE => $this->handleDelete($request),
            Http::METHOD_PUT    => $this->handlePut($request),
            default => new EmptyResponse(405),
        };
    }

    public function handleDelete(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(405);
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(405);
    }

    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(405);
    }

    public function handlePut(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(405);
    }
}
