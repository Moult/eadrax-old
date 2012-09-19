<?php
/**
 * Eadrax spec/bootstrap.php
 *
 * Bootstraps necessary Kohana dependencies to run PHPSpec tests.
 * From docroot, run `phpspec spec/ --bootstrap spec/bootstrap.php`
 *
 * @package   Spec
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

$application = 'application';
$modules = 'modules';
$system = 'system';

define('EXT', '.php');

error_reporting(E_ALL | E_STRICT);

define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules).DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system).DIRECTORY_SEPARATOR);

unset($application, $modules, $system);

require APPPATH.'bootstrap'.EXT;
