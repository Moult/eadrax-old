<?php
/**
 * Eadrax application/classes/exception/multiple.php
 *
 * @package   Exception
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * For exceptions that need to log an array rather than a single message
 *
 * throw new Exception_Multiple_Subclass(array());
 * catch (Exception_Multiple_Subclass $e) {
 *     $issues = $e->as_array();
 * }
 *
 * @package Exception
 */
class Exception_Multiple extends Exception
{
    /**
     * Holds the array of exception error messages
     */
    public $errors = array();

    /**
     * Stores the array of exception messages
     *
     * @param array $errors An array of exception messages
     *
     * @return void
     */
    public function __construct(array $errors)
    {
        parent::__construct('Multiple exceptions thrown.');
        $this->errors = $errors;
    }

    /**
     * Gives all the messages as an array
     *
     * @return array
     */
    public function as_array()
    {
        return $this->errors;
    }
}
