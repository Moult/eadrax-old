<?php
/**
 * Eadrax Exception/Validation.php
 *
 * @package   Exception
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Exception;

/**
 * When validation fails in a usecase execution
 *
 * throw new Exception_Validation($validation_instance->errors());
 *
 * @package Exception
 */
class Validation extends Multiple {}
