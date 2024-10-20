<?php

declare(strict_types=1);

namespace App\Tooling;

final class TemplateSkeletons
{
    public const HTMX_SKELETON = <<<'EOS'
    <?php $this->headTitle($this->title); ?>

    <?=$this->header?>
    <main id="app-main" class="container">
        <article>
            <div>
                <h2 class="pico-color-jade-500">Default Heading</h2>
                <p>
                    Paragraph One
                </p>
                <p>
                    Paragraph Two
                </p>
            </div>
        </article>
    </main>
    <?=$this->footer?>
    EOS;
}
