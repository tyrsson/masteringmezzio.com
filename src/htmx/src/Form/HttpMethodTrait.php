<?php

declare(strict_types=1);

namespace Htmx\Form;

use Fig\Http\Message\RequestMethodInterface as Http;

use function in_array;

trait HttpMethodTrait
{
    final public const ALLOWED_METHODS = [
        Http::METHOD_POST, Http::METHOD_PUT, Http::METHOD_PATCH
    ];

    protected ?string $httpMethod = Http::METHOD_POST;

    public function setHttpMethod(string $httpMethod = Http::METHOD_POST): void
    {
        if (in_array($httpMethod, static::ALLOWED_METHODS)) {
            $this->httpMethod = $httpMethod;
        }
    }

    public function getHttpMethod(): ?string
    {
        return $this->httpMethod;
    }
}
