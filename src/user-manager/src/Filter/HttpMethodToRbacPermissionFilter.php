<?php

declare(strict_types=1);

namespace UserManager\Filter;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Filter;

use function str_ends_with;

final class HttpMethodToRbacPermissionFilter implements Filter\FilterInterface
{
    final public const TO_HTTP_METHOD     = 'toHttp';
    final public const TO_RBAC_PERMISSION = 'toPerm';
    final public const CREATE_PERMISSION  = 'create';
    final public const DELETE_PERMISSION  = 'delete';
    final public const READ_PERMISSION    = 'read';
    final public const UPDATE_PERMISSION  = 'update';

    private array $map = [
        Http::METHOD_POST   => self::CREATE_PERMISSION,
        Http::METHOD_GET    => self::READ_PERMISSION,
        Http::METHOD_PUT    => self::UPDATE_PERMISSION,
        Http::METHOD_PATCH  => self::UPDATE_PERMISSION,
        Http::METHOD_DELETE => self::DELETE_PERMISSION,
    ];


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
