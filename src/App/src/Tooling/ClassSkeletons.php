<?php

declare(strict_types=1);

namespace App\Tooling;

class ClassSkeletons
{
    /**
     * @var string Template for request handler class.
     */
    public const CLASS_SKELETON = <<<'EOS'
        <?php

        declare(strict_types=1);

        namespace %namespace%;

        use App\HandlerTrait;
        use Psr\Http\Message\ResponseInterface;
        use Psr\Http\Message\ServerRequestInterface;
        use Psr\Http\Server\RequestHandlerInterface;

        class %class% implements RequestHandlerInterface
        {
            use HandlerTrait;

            public function handleGet(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }

            public function handlePost(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }

            public function handlePut(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }

            public function handleDelete(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }
        }

        EOS;

    /**
     * @var string Template for request handler class that will render a template.
     */
    public const CLASS_SKELETON_WITH_TEMPLATE = <<<'EOS'
        <?php

        declare(strict_types=1);

        namespace %namespace%;

        use App\HandlerTrait;
        use Psr\Http\Message\ResponseInterface;
        use Psr\Http\Message\ServerRequestInterface;
        use Psr\Http\Server\RequestHandlerInterface;
        use Laminas\Diactoros\Response\HtmlResponse;
        use Laminas\View\Model\ModelInterface;
        use Mezzio\Template\TemplateRendererInterface;

        class %class% implements RequestHandlerInterface
        {
            use HandlerTrait;

            public function __construct(
                private TemplateRendererInterface $renderer
            ) {
            }

            public function handleGet(ServerRequestInterface $request): ResponseInterface
            {
                $data = [
                    'title' => '%template-name%',
                ];
                $model = $request->getAttribute(ModelInterface::class);
                $model->setVariables($data);
                return new HtmlResponse($this->renderer->render(
                    '%template-namespace%::%template-name%',
                    $model
                ));
            }

            public function handlePost(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }

            public function handlePut(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }

            public function handleDelete(ServerRequestInterface $request): ResponseInterface
            {
                // Create and return a response
            }
        }

        EOS;
}
