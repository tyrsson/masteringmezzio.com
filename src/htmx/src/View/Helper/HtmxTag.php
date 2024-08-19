<?php

declare(strict_types=1);

namespace Htmx\View\Helper;

use Laminas\View\Helper\HtmlTag;

use function method_exists;
use function sprintf;

class HtmxTag extends HtmlTag
{
    private ?string $tag = 'div';
    private string $content = '';

    public function __invoke(?array $attribs = [], ?string $tag = null, ?string $content = null)
    {
        if (! empty($attribs)) {
            $this->setAttributes($attribs);
        }
        if (! empty($tag)) {
            $this->tag = $tag;
        }
        if (! empty($content)) {
            $this->content = $content;
        }
        return $this;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function openTag(string $tag = null): string
    {
        if (null !== $tag) {
            $this->tag = $tag;
        }
        return sprintf(
            '<%s%s>%s',
            $this->tag,
            $this->htmlAttribs($this->attributes),
            $this->content
        );
    }

    public function closeTag(): string
    {
        return sprintf('</%s>', $this->tag);
    }

    public function __toString()
    {
        return $this->openTag() . $this->closeTag();
    }
}
