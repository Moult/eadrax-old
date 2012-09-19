<?php
/**
 * Eadrax application/classes/exception/validation.php
 *
 * @package   Exception
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * When validation fails in a usecase execution
 *
 * throw new Exception_Validation($validation_instance->errors());
 *
 * @package Exception
 */
class Exception_Validation extends Exception_Multiple {}
