<?php

declare(strict_types=1);

namespace Htmx;

/**
 * @link https://htmx.org/reference/#response_headers
 */
enum ResponseHeaders: string
{
    case HX_Location             = 'HX-Location';
    case HX_Push_Url             = 'HX-Push-Url';
    case HX_Redirect             = 'HX-Redirect';
    case HX_Refresh              = 'HX-Refresh';
    case HX_Replace_Url          = 'HX-Replace-Url';
    case HX_Reswap               = 'HX-Reswap';
    case HX_Retarget             = 'HX-Retarget';
    case HX_Reselect             = 'HX-Reselect';
    case HX_Trigger              = 'HX-Trigger';
    case HX_Trigger_After_Settle = 'HX-Trigger-After-Settle';
    case HX_Trigger_After_Swap   = 'HX-Trigger-After-Swap';
}
