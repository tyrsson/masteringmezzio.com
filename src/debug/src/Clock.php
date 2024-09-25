<?php

declare(strict_types=1);

namespace Debug;

use DateTimeImmutable;
use DateTimeZone;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Profiler\Profiler;
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

    public static function timer(?string $name = null): string|float
    {
        static $time = [];
        $now = hrtime(true);
        $delta = isset($time[$name]) ? $now - $time[$name] : 0;
        $time[$name] = $now;
        $elapsed = $delta / 1e+6;
        return '<pre> ' . $name . ' ' . $elapsed . ' ms</pre>';
    }

    public static function queryProfiles(AdapterInterface&Adapter $adapter)
    {
        /** @var Profiler */
        $profiler = $adapter->getProfiler();
        foreach ($profiler->getProfiles() as $profile) {
            ['sql' => $sql, 'elapse' => $elapse] = $profile;
            Dumper::dump(
                [
                    'sql' => $sql,
                    'elapsed-time' => number_format($elapse / 1000, 5, '.', "\u{202f}") . ' ms',
                ],
                'Query Profile:'
            );
        }
    }
}
