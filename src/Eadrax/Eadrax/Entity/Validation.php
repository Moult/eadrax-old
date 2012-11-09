<?php
/**
 * Eadrax Entity/Validation.php
 *
 * @package   Entity
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Entity;

/**
 * Allows for validation of user input
 *
 * @package Entity
 */
interface Validation
{
    /**
     * Loads in the input data from the user that you want to perform validation 
     * checks on.
     *
     * Example:
     * $validation = new Validation_Class;
     * $validation->setup($_POST);
     *
     * @param array input_data
     * @return void
     */
    public function setup(array $input_data);

    /**
     * Adds a rule for checking the input with key $key using a predefined rule.
     *
     * Example:
     * $validation->rule('username', 'not_empty');
     *
     * @param string $key  The key to access the input data value
     * @param string $rule not_empty - passes if there is a value
     *                     regex - passes if it matches the regex in $arg
     *                     min_length - passes if chars are more than $arg
     *                     max_length - passes if chars are less than $arg
     *                     email - passes if it is a valid email
     * @param string $arg  Any extra arguments related to the rule
     * @return void
     */
    public function rule($key, $rule, $arg = NULL);

    /**
     * Adds a custom function to validate the input with key $key.
     *
     * Example:
     * // will call $this->is_existing_account();
     * $validation->callback($this, 'is_existing_account');
     *
     * @param string $key      The key to access the input data value
     * @param array  $function Array($object, string $function_name) which has a 
     *                         return type of bool
     * @return void
     */
    public function callback($key, array $function, array $args);

    /**
     * Runs all of the added rules and callbacks, logging whether or not there 
     * are any validation errors.
     *
     * Example:
     * if ($validation->check() === TRUE) {} // All items are valid.
     *
     * @return bool
     */
    public function check();

    /**
     * Returns an array with any errors found.
     *
     * Example:
     * print_r($validation->check()); // return array('keyfoo', 'keybar')
     *
     * @return array
     */
    public function errors();
}
