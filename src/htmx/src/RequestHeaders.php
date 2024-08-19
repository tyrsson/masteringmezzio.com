<?php

declare(strict_types=1);

namespace Htmx;
/**
 * @link https://htmx.org/reference/#request_headers
 */
Enum RequestHeaders: string
{
    case HX_Boosted                 = 'HX-Boosted';
    case HX_Current_Url             = 'HX-Current-Url';
    case HX_History_Restore_Request = 'HX-History-Restore-Request';
    case HX_Prompt                  = 'HX-Prompt';
    case HX_Request                 = 'HX-Request';
    case HX_Target                  = 'HX-Target';
    case HX_Trigger_Name            = 'HX-Trigger-Name';
    case HX_Trigger                 = 'HX-Trigger';
}
