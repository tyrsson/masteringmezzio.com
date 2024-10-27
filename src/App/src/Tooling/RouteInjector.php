<?php

declare(strict_types=1);
// Original namespace Mezzio\Tooling\Factory
namespace App\Tooling;

use App\Config\Writer\PhpArray;
use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Config\Factory;
use Laminas\Filter\Word\SeparatorToSeparator;

use function array_unshift;
use function array_push;
use function file_exists;
use function rtrim;
use function sprintf;
use function ucwords;

/**
 * Inject factory configuration in an autoloadable location.
 *
 * This class will re-generate the file denoted by its CONFIG_FILE constant.
 * It first pulls in the data in that file, if the file exists, and then adds
 * an entry for the given class, pointing it to the given factory, rewriting
 * the configuration file on completion.
 */
final class RouteInjector
{
    /**
     * @var string
     */
    public const CONFIG_FILE = 'config/autoload/mezzio-tooling-routes.global.php';

    private const RBAC_CONFIG_KEY = 'mezzio-authorization-rbac';

    private string $configFile;

    public function __construct(
        private string $projectRoot,
        private ?array $rbacConfig = null
    ) {
        $this->configFile = $projectRoot === ''
            ? self::CONFIG_FILE
            : sprintf('%s/%s', rtrim($projectRoot, '/'), self::CONFIG_FILE);
    }

    public function injectRouteForHandler(
        string $handler,
        string $templateName,
        ?string $middleware = null,
        ?string $role = null
    ): string {

        $config = [];
        $filter    = new SeparatorToSeparator('-', ' ');
        $routeName = ucwords($filter->filter($templateName));

        $config['routes'] = [];
        if (file_exists($this->configFile)) {
            $config = Factory::fromFile($this->configFile);
        }

        if (! empty($this->rbacConfig)) {
            $config[self::RBAC_CONFIG_KEY]['permissions'][$role][] = $routeName;
        }

        $writer = new PhpArray();
        $writer->setUseBracketArraySyntax(true);

        $route = [
            'path' => '/' . $templateName,
            'name' => $routeName,
            'middleware' => [
                $handler,
            ],
            'allowed_methods' => [
                Http::METHOD_DELETE, Http::METHOD_GET, Http::METHOD_POST, Http::METHOD_PUT
            ],
        ];
        if ($middleware) {
            array_unshift($route['middleware'], $middleware);
        }
        $config['routes'][] = $route;
        $writer->toFile($this->configFile, $config);
        return $this->configFile;
    }
}
