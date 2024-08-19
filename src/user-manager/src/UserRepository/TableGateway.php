<?php

declare(strict_types=1);

namespace UserManager\UserRepository;

use Axleus\Db;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Authentication\Exception;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Webmozart\Assert\Assert;

use function password_verify;

final class TableGateway extends Db\AbstractRepository implements UserRepositoryInterface
{
    /**
     * @var callable
     * @psalm-var callable(string, array<int|string, string>, array<string, mixed>): UserInterface
     */
    private $userFactory;

    public function __construct(
        protected Db\TableGateway $gateway,
        callable $userFactory,
        protected $hydrator = new ReflectionHydrator(),
        private array $config = []
    ) {
        parent::__construct($gateway, $hydrator);
        // Provide type safety for the composed user factory.
        $this->userFactory = static function (
            string $identity,
            array $roles = [],
            array $details = []
        ) use ($userFactory): UserInterface {
            Assert::allString($roles);
            Assert::isMap($details);

            return $userFactory($identity, $roles, $details);
        };
    }

    public function authenticate(string $credential, ?string $password = null): ?UserInterface
    {
        /** @var App\UserRepository\UserEntity */
        $user = $this->findOneBy($this->config['username'], $credential);
        $hash = $user->getPassword();
        $this->checkBcryptHash($hash);
        if (password_verify($password, $hash)) {
            return ($this->userFactory)(
                $credential,
                $user->getRoles(),
                $user->getDetails()
            );
        }
        return null;
    }

    /**
     * Check bcrypt usage for security reason
     *
     * @throws Exception\RuntimeException
     */
    protected function checkBcryptHash(string $hash): void
    {
        if (0 !== strpos($hash, '$2y$')) {
            throw new Exception\RuntimeException(
                'The provided hash has not been created with a supported algorithm. Please use bcrypt.'
            );
        }
    }
}
