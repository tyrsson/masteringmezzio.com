<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;
use DateTimeZone;

use Psr\Clock\ClockInterface;

use function hrtime;
use function number_format;

class Clock implements ClockInterface
{
    public function __construct(
        private ?DateTimeZone $dateTimeZone = null
    ) {
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            timezone: $this->dateTimeZone
        );
    }

    /**
     * Uses hrtime
     * @param null|string $name
     * @return string|float
     */
    public static function timer(?string $name = 'default_timer'): string|float
    {
        static $time = [];
        $now = hrtime(true);
        $delta = isset($time[$name]) ? $now - $time[$name] : 0;
        $time[$name] = $now;
        $elapsed = $delta / 1e+6;
        return $name . ' - ' . $elapsed . ' ms';
    }
}
