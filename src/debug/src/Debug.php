<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Debug
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Debug.php 24594 2012-01-05 21:27:01Z matthew $
 */

declare(strict_types=1);

namespace Debug;

use Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Profiler\Profiler;
use RuntimeException;

use function basename;
use function extension_loaded;
use function htmlspecialchars;
use function ob_get_clean;
use function ob_start;
use function preg_replace;
use function rtrim;
use function strlen;
use function var_dump;

use const ENT_QUOTES;
use const PHP_EOL;
use const PHP_SAPI;

/**
 * Concrete class for generating debug dumps related to the output source.
 *
 * @category   Zend
 * @package    Zend_Debug
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

class Debug
{
    /** @var string */
    protected static $_sapi;

    /**
     * Get the current value of the debug output environment.
     * This defaults to the value of PHP_SAPI.
     *
     * @return string;
     */
    public static function getSapi()
    {
        if (self::$_sapi === null) {
            self::$_sapi = PHP_SAPI;
        }
        return self::$_sapi;
    }

    /**
     * Set the debug ouput environment.
     * Setting a value of null causes Zend_Debug to use PHP_SAPI.
     *
     * @param string $sapi
     * @return void;
     */
    public static function setSapi($sapi)
    {
        self::$_sapi = $sapi;
    }

    /**
     * Debug helper function.  This is a wrapper for var_dump() that adds
     * the <pre /> tags, cleans up newlines and indents, and runs
     * htmlentities() before output.
     *
     * @param  mixed  $var   The variable to dump.
     * @param  string $label OPTIONAL Label to prepend to output.
     * @param  bool   $echo  OPTIONAL Echo output if true.
     * @return string
     */
    public static function dump($var, $label = '', $showFullPath = false, $echo = true)
    {
        if (strlen($label) >= 1) {
            $label = $label . PHP_EOL;
        }

        try {
            throw new Exception();
        } catch (Exception $ex) {
            $trace = $ex->getTrace();
            $file  = $showFullPath ? $trace[0]['file'] : basename($trace[0]['file']);
            $label .= 'Dumped from file: ' . $file . ' on line: ' . $trace[0]['line'];
        }
        // format the label
        $label = rtrim($label) . ' ';

        // var_dump the variable into a buffer and keep the output
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        if (self::getSapi() === 'cli') {
            $output = PHP_EOL . $label
                    . PHP_EOL . $output
                    . PHP_EOL;
        } else {
            if (! extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, ENT_QUOTES);
            }

            $output = '<pre>'
                    . $label
                    . $output
                    . '</pre>';
        }

        if ($echo) {
            echo $output;
        }
        return $output;
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

    public static function dbDebug(AdapterInterface&Adapter $adapter)
    {
        /** @var Profiler */
        $profiler = $adapter->getProfiler();
        if (! $profiler instanceof Profiler) {
            throw new RuntimeException(Adapter::class . ' Must have a composed ' . Profiler::class . ' instance before calling dbDebug.');
        }
        foreach ($profiler->getProfiles() as $profile) {
            ['sql' => $sql, 'elapse' => $elapse] = $profile;
            static::dump(
                [
                    'sql' => $sql,
                    'elapsed-time' => number_format($elapse * 1000, 5, '.', "\u{202f}") . ' ms',
                ],
                'Query Profile:'
            );
        }
    }
}
