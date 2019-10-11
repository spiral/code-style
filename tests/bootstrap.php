<?php
/**
 * Spiral Framework. Codestyle
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Alex Novikov
 */
// phpcs:ignoreFile
declare(strict_types=1);

define('SPIRAL_INITIAL_TIME', microtime(true));

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
mb_internal_encoding('UTF-8');

//Composer
require dirname(__DIR__) . '/vendor/autoload.php';

//Set to true to enter the Matrix
define('ENABLE_PROFILING', false);
