<?php
/*
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

// relativize the include path to the correct level
set_include_path( realpath(__DIR__ . "/../../") . PATH_SEPARATOR . get_include_path() );

require_once "Doctrine/Common/ClassLoader.php";

$loader = new \Doctrine\Common\ClassLoader("Doctrine");
$loader->register();

$loader = new \Doctrine\Common\ClassLoader('DoctrineExtensions');
$loader->register();

spl_autoload_register(function($class) {
    if (strpos($class, "Whitewashing\\") === 0) {
        $file = str_replace(array("Whitewashing", "\\"), array("", "/"), $class). ".php";
        require __DIR__ . "/../" . $file;
    }
});

require_once "ezc/Base/base.php";
spl_autoload_register( array( 'ezcBase', 'autoload' ) );