<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Exception;

class Validation extends \Exception
{
    private $errors = array();

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function get_errors()
    {
        return $this->errors;
    }
}
