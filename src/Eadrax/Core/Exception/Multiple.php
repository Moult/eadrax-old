<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Exception;

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
