<?php

declare(strict_types=1);

namespace Htmx\View\Model;

use Laminas\View\Model\ViewModel;

final class FooterModel extends ViewModel
{
    /**
     * What variable a parent model should capture this model to
     *
     * @var string
     */
    protected $captureTo = 'footer';

    /**
     * Template to use when rendering this model
     *
     * @var string
     */
    protected $template = 'app::footer';
}
