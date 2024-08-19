<?php

declare(strict_types=1);

namespace App\Middleware;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContactMiddleware implements MiddlewareInterface
{
    public function __construct(
        private InputFilterInterface $inputFilter,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Http::METHOD_POST === $request->getMethod()) {
            $post = $request->getParsedBody();
            $this->inputFilter->setData($post);
            if ($this->inputFilter->isValid()) {
                /**
                 * As you have probably noticed, this does not currently send the email
                 * it was just enough so that I could go ahead and work out the server/client
                 * interaction to make sure the request/response was working as expected. However,
                 * with that said, the email will be sent from here.
                 * todo: add sending email functionality
                 */
                $request = $request->withAttribute('emailSent', true);
            } else {
                // if validation fails the handler will need access to the error messages
                $request = $request->withAttribute('emailSent', false);
                $request = $request->withAttribute('formError', $this->inputFilter->getMessages());
            }
        }
        return $handler->handle($request);
    }
}
