<?php

declare(strict_types=1);

namespace UserManager\Authz;

use Laminas\Permissions\Rbac\Rbac;
use Laminas\Permissions\Rbac\RoleInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authorization\Rbac\LaminasRbacAssertionInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserAssertion implements LaminasRbacAssertionInterface
{
    private ServerRequestInterface $request;

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function assert(Rbac $rbac, RoleInterface $role, string $permission): bool
    {
        return match ($permission) {
            'Login' => $this->assertUserIsGuest($role),
             default => true,
        };
    }

    private function assertUserIsGuest(RoleInterface $role): bool
    {
        $user = $this->request->getAttribute(UserInterface::class, false);
        return $user->getIdentity() === 'guest';
    }
}
