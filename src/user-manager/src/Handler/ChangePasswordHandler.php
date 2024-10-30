<?php

declare(strict_types=1);

namespace UserManager\Handler;

use App\HandlerTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Model\ViewModel;
use Mezzio\Template\TemplateRendererInterface;
use UserManager\Form\ChangePassword;

class ChangePasswordHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ChangePassword $form
    ) {
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ViewModel */
        $model = $request->getAttribute(ModelInterface::class);
        $model->setVariable('form', $this->form);

        return new HtmlResponse($this->renderer->render(
            'user-manager::change-password',
            $model
        ));
    }
}
