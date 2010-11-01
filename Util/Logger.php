<?php
/**
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */
namespace Whitewashing\Util;

class Logger
{
    /**
     * @var Zend_Log
     */
    static public $log = null;

    static public function log($message, $level = null)
    {
        if ($level == null) {
            $level = \Zend_Log::INFO;
        }

        if (self::$log) {
            self::$log->log($message, $level);
        }
    }
}