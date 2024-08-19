<?php

declare(strict_types=1);

namespace Htmx;

enum HtmxAttributes: string
{
    /**
     * @link https://htmx.org/reference/#attributes
     */
    case HX_Get         = 'hx-get';
    case HX_Post        = 'hx-post';
    case HX_On          = 'hx-on';
    case HX_Push_Url    = 'hx-push-url';
    case HX_Select      = 'hx-select';
    case HX_Select_Oob  = 'hx-select-oob';
    case HX_Swap        = 'hx-swap';
    case HX_Swap_Oob    = 'hx-swap-oob';
    case HX_Target      = 'hx-target';
    case HX_Trigger     = 'hx-trigger';
    case HX_Vals        = 'hx-vals';
    /**
     * @link https://htmx.org/reference/#attributes-additional
     */
    case HX_Boost       = 'hx-boost';
    case HX_Confirm     = 'hx-confirm';
    case HX_Delete      = 'hx-delete';
    case HX_Disable     = 'hx-disable';
    case HX_Disable_Elt = 'hx-disable-elt';
    case HX_Disinherit  = 'hx-disinherit';
    case HX_Encoding    = 'hx-encoding';
    case HX_Ext         = 'hx-ext';
    case HX_Headers     = 'hx-headers';
    case HX_History     = 'hx-history';
    case HX_History_Elt = 'hx-history-elt';
    case HX_Include     = 'hx-include';
    case HX_Indicator   = 'hx-indicator';
    case HX_Inherit     = 'hx-inherit';
    case HX_Params      = 'hx-params';
    case HX_Patch       = 'hx-patch';
    case HX_Preserve    = 'hx-preserve';
    case HX_Prompt      = 'hx-prompt';
    case HX_Put         = 'hx-put';
    case HX_Replace_Url = 'hx-replace-url';
    case HX_Request     = 'hx-request';
    case HX_Sync        = 'hx-sync';
    case HX_Validate    = 'hx-validate';
    case HX_Vars        = 'hx-vars';
}
