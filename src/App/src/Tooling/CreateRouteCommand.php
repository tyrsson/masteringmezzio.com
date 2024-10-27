<?php

declare(strict_types=1);
// Original namespace Mezzio\Tooling\Factory
namespace App\Tooling;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

final class CreateRouteCommand extends Command
{
    /**
     * @var string
     */
    public const CONFIG_FILE = 'config/autoload/mezzio-tooling-routes.global.php';

    /**
     * @var string
     */
    public const HELP = <<<'EOT'
        Creates a route for the provided handler.
        EOT;

    /**
     * @var string
     */
    public const HELP_ARG_HANDLER = <<<'EOT'
        Fully qualified class name of the handler for which to create a route.
        This value should be quoted to ensure namespace separators are not
        interpreted as escape sequences by your shell. The handler should be
        autoloadable.
        EOT;

    public const HELP_ARG_ROUTE_NAME = <<<'EOT'
        The name route name.
        EOT;

    public const HELP_ARG_MIDDLEWARE = <<<'EOT'
        Fully qualified class name of the middleware to pipe before handler if any.
        This value should be quoted to ensure namespace seperators are not
        interpreted as escape sequences by your shell. The middleware should be
        autoloadable.
        EOT;

    public const HELP_OPT_NO_ROUTE = <<<'EOT'
        By default when this command generates a handler it creates and registers a
        route in the container. Passing this option disables route creation and
        registration with the container.
        EOT;

    public const HELP_OPT_AUTHZ_ROLE = <<<'EOT'
        The minimum RBAC role you would like to authorize for this route if any.
        Defaults to Guest. Valid options are Guest, User, Administrator.
        EOT;

    /** @var null|string Cannot be defined explicitly due to parent class */
    public static $defaultName = 'mezzio:route:create';

    public function __construct(
        private string $projectRoot,
        private ?array $rbacConfig = null
    ) {
        parent::__construct();
    }

    /**
     * Configure the console command.
     */
    protected function configure(): void
    {
        $this->setDescription('Create a route entry for the named handler.');
        $this->setHelp(self::HELP);
        $this->addArgument('handler', InputArgument::REQUIRED, self::HELP_ARG_HANDLER);
        $this->addArgument('route-name', InputArgument::REQUIRED, self::HELP_ARG_ROUTE_NAME);
        $this->addArgument('middleware', InputArgument::OPTIONAL, self::HELP_ARG_MIDDLEWARE);
        $this->addOption('no-route', null, InputOption::VALUE_NONE, self::HELP_OPT_NO_ROUTE);
        $this->addOption('authorize-role', 'rbac', InputOption::VALUE_OPTIONAL, self::HELP_OPT_AUTHZ_ROLE, 'Guest');
    }

    /**
     * Execute console command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $handler       = (string) $input->getArgument('handler');
        $routeName     = (string) $input->getArgument('route-name');
        $registerRoute = ! $input->getOption('no-route');
        $configFile    = null;

        $output->writeln(sprintf('<info>Creating route for handler %s...</info>', $handler));

        if ($registerRoute) {
            $output->writeln('<info>Registering route with container</info>');
            $injector   = new RouteInjector($this->projectRoot, $this->rbacConfig);
            $configFile = $injector->injectRouteForHandler(
                $handler,
                $routeName,
                $input->getArgument('middleware'),
                $input->getOption('authorize-role')
            );
        }

        $output->writeln('<info>Success!</info>');
        $output->writeln(sprintf(
            '<info>- Created route entry for %s, in file %s</info>',
            $handler,
            $configFile
        ));

        return 0;
    }
}
