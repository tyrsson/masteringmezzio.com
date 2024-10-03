<?php

declare(strict_types=1);

namespace UserManager\Helper;

use DateInterval;
use DateTimeImmutable;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Rfc4122\UuidV7;
use UserManager\UserRepository\TableGateway;
use UserManager\UserRepository\UserEntity;
use UserManager\Validator\UuidV7TokenValidator as TokenValidator;

final class VerificationHelper
{
    private UserEntity $target;

    public function __construct(
        private UserRepositoryInterface&TableGateway $userRepositoryInterface,
        private array $config
    ) {
    }

    public function __invoke(?ServerRequestInterface $request = null): self|UserEntity|false
    {
        if ($request !== null) {
            return $this->verify($request);
        }
        return $this;
    }

    public function verify(ServerRequestInterface $request, string $tokenLifetime = '1 Hour'): UserEntity|false
    {
        $routeResult   = $request->getAttribute(RouteResult::class);
        $matchedParams = $routeResult->getMatchedParams();
        try {
            if (empty($this->target)) {
                $this->target = $this->userRepositoryInterface->findOneBy('id', $matchedParams['id']);
            }

            if ($matchedParams['token'] === $this->target->offsetGet('verificationToken')) {
                $tokenValidator = new TokenValidator([
                        'max_lifetime' => $tokenLifetime,
                    ]);
                if ($tokenValidator->isValid($this->target->offsetGet('verificationToken'))) {
                    $now       = new DateTimeImmutable();
                    $timeStamp = $now->format($this->config['app_settings']['datetime_format']);
                    $this->target->offsetSet(
                        'dateVerified',
                        $timeStamp
                    );
                    $this->target->offsetSet('dateUpdated', $timeStamp);
                    $this->target->offsetSet('verified', 1);
                    $this->target->offsetSet('verificationToken', null);
                    $this->target = $this->userRepositoryInterface->save($this->target, 'id');
                    return $this->target;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return false;
    }

    public function verifyResetToken()
    {

    }

    public function setTarget(UserEntity $target): void
    {
        $this->target = $target;
    }

    public function getTarget(): UserEntity
    {
        return $this->target;
    }
}
