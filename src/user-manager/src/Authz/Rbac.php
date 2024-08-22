<?php

declare(strict_types=1);

namespace UserManager\Authz;

use Laminas\Permissions\Rbac\Rbac as LaminasRbac;
use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Authorization\Rbac\LaminasRbacAssertionInterface;
use Mezzio\Authorization\Exception;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;
use UserManager\Filter\HttpMethodToRbacPermissionFilter;

use function assert;
use function is_string;
use function sprintf;
use function strtolower;

class Rbac implements AuthorizationInterface
{
    private HttpMethodToRbacPermissionFilter $filter;

    public function __construct(
        private LaminasRbac $rbac,
        private ?LaminasRbacAssertionInterface $assertion = null
    ) {
        $this->filter = new HttpMethodToRbacPermissionFilter();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception\RuntimeException
     */
    public function isGranted(string $role, ServerRequestInterface $request): bool
    {
        $routeResult = $request->getAttribute(RouteResult::class, false);
        if (! $routeResult instanceof RouteResult) {
            throw new Exception\RuntimeException(sprintf(
                'The %s attribute is missing in the request; cannot perform authorizations',
                RouteResult::class
            ));
        }

        // No matching route. Everyone can access.
        if ($routeResult->isFailure()) {
            return true;
        }

        $routeName = $routeResult->getMatchedRouteName();

        // append mapped http method to the routeName to match the crud permissions.
        $routeName = $this->filter->filter($routeName, $request->getMethod());

        assert(is_string($routeName));
        if (null !== $this->assertion) {
            $this->assertion->setRequest($request);
        }

        return $this->rbac->isGranted($role, $routeName, $this->assertion);
    }
}
