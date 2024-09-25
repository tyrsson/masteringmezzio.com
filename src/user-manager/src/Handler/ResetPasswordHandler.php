<?php

declare(strict_types=1);

namespace UserManager\Handler;

use App\HandlerTrait;
use App\SystemMessageInterface;
use Fig\Http\Message\RequestMethodInterface as Http;
use Htmx\HtmxHandlerTrait;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ModelInterface;
use Mezzio\Flash\FlashMessages;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UserManager\Form\ResetPassword;

class ResetPasswordHandler implements RequestHandlerInterface
{
    use HandlerTrait;
    use HtmxHandlerTrait;

    public final const MESSAGE_KEY = 'reset_notice_message';

    public function __construct(
        private TemplateRendererInterface $renderer,
        private UrlHelper $url,
        private ResetPassword $form
    ) {
    }

    private function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $model = $request->getAttribute(ModelInterface::class);
        $model->setVariable('form', $this->form);
        return new HtmlResponse(
            $this->renderer->render(
                'user-manager::reset-password',
                $model
            )
        );
    }

    private function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $this->form->setData($body);
        if ($this->form->isValid()) {
            // send mail
        }
        /** @var FlashMessages */
        $messenger = $request->getAttribute(SystemMessageInterface::class);
        $messenger->flash(
            static::MESSAGE_KEY,
            'If the requested email address was found a reset email will be sent to that account.'
        );
        $model = $request->getAttribute(ModelInterface::class);
        return new HtmlResponse(
            html: $this->renderer->render(
                'user-manager::reset-password',
                $model
            )
        );
    }

    private function handlePut(ServerRequestInterface $request): ResponseInterface
    {

    }

    private function handleDelete(ServerRequestInterface $request): ResponseInterface
    {

    }
}
