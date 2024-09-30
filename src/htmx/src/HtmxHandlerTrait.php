<?php

declare(strict_types=1);

namespace Htmx;

use Htmx\ResponseHeaders as HtmxHeader;

use function json_encode;

trait HtmxHandlerTrait
{
    private string $domTarget = '#app-main';

    private function hxLocation(array $params): array
    {
        $data = ['target' => $this->domTarget];
        $data = $params + $data;
        return [HtmxHeader::HX_Location->value => json_encode($data)];
    }
}
