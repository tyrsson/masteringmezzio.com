<?php

declare(strict_types=1);

namespace Htmx;

use Htmx\ResponseHeaders as Htmx;

use function json_encode;

trait HtmxTriggerTrait
{
    final public const UI_EVENT = 'systemMessage'; // this is our event so lets support it as default
    private array $headers;

    protected function htmxTrigger(
        array $data,
        ?string $event = self::UI_EVENT
    ): void {
        $this->headers[Htmx::HX_Trigger->value] = json_encode([$event => $data]);
    }
}
