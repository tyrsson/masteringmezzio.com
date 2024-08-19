<?php

declare(strict_types=1);

namespace Htmx\Form;

use Htmx\HtmxAttributes as Htmx;
use Mezzio\Helper\UrlHelper;

trait HtmxTrait
{
    protected bool $boost  = true;
    protected bool $enable = true;
    protected UrlHelper $urlHelper;

    public function enable(bool $enable = true): bool
    {
        if ($this->enable !== $enable) {
            $this->enable = $enable;
        }
        return $this->enable;
    }

    public function boost(bool $boost = true): void
    {
        if ($this->enable && $boost) {
            $this->setAttribute(Htmx::HX_Boost->value, 'true');
        }
    }

    public function setUrlHelper(UrlHelper $helper): void
    {
        $this->urlHelper = $helper;
    }

    public function getUrlHelper(): UrlHelper
    {
        return $this->urlHelper;
    }
}
