<?php

declare(strict_types=1);

namespace UserManager\Filter;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Filter;
use Psr\Http\Message\ServerRequestInterface;

use function in_array;
use function strtolower;
use function str_ends_with;

final class HttpMethodToRbacPermissionFilter implements Filter\FilterInterface
{
    final public const TO_HTTP_METHOD     = 'toHttp';
    final public const TO_RBAC_PERMISSION = 'toPerm';
    final public const CREATE_PERMISSION  = 'create';
    final public const DELETE_PERMISSION  = 'delete';
    final public const READ_PERMISSION    = 'read';
    final public const UPDATE_PERMISSION  = 'update';

    /** @var Filter\FilterChain $filterChain */
    private $filterChain;
    private array $map = [
        Http::METHOD_POST   => self::CREATE_PERMISSION,
        Http::METHOD_GET    => self::READ_PERMISSION,
        Http::METHOD_PUT    => self::UPDATE_PERMISSION,
        Http::METHOD_PATCH  => self::UPDATE_PERMISSION,
        Http::METHOD_DELETE => self::DELETE_PERMISSION,
    ];


    // public function __construct()
    // {
    //     $this->filterChain = new Filter\FilterChain();
    //     $this->filterChain->attach(new Filter\StringToLower())->attach(new Filter\Word\SeparatorToDash(' '));
    // }
    /** @inheritDoc */
    public function filter($value, ?string $method = '')
    {
        $mappedPermission = $this->map[$method];
        if (! str_ends_with($value, $mappedPermission)) {
            $value .= '.' . $mappedPermission;
        }
        return $value;
    }
}
