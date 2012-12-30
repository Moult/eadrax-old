<?php
/**
 * Eadrax Exception/Multiple.php
 *
 * @package   Exception
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Exception;

/**
 * For exceptions that need to log an array rather than a single message
 *
 * throw new Exception_Multiple_Subclass(array());
 * catch (Exception_Multiple_Subclass $e) {
 *     $issues = $e->get_errors();
 * }
 *
 * @package Exception
 */
class Multiple extends \Exception
{
    /**
     * Holds the array of exception error messages
     */
    private $errors = array();

    /**
     * Stores the array of exception messages
     *
     * @param array $errors An array of exception messages
     * @return void
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function get_errors()
    {
        return $this->errors;
    }
}
