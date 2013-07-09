<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Validator
{
    /**
     * Loads in the input data from the user that you want to perform validation
     * checks on.
     *
     * Example:
     * $validator->setup($_POST);
     *
     * @param array $input_data
     * @return void
     */
    public function setup(array $input_data);

    /**
     * Adds a rule for checking the input with key $key using a predefined rule.
     *
     * Example:
     * $validator->rule('username', 'not_empty');
     *
     * @param string $key  The key to access the input data value
     * @param string $rule not_empty - passes if there is a value
     *                     regex - passes if it matches the regex in $arg
     *                     min_length - passes if chars are more than $arg
     *                     max_length - passes if chars are less than $arg
     *                     exact_length - passes if chars are exactly $arg
     *                     email - passes if it is a valid email
     *                     email_domain - passes if it is a valid email domain MX
     *                     url - passes if it is a valid url
     *                     url_domain - passes if the url dommain is valid and accessible
     *                     ip - passes if valid ip address
     *                     date - passes if is a valid date
     *                     matches - passes if matches another key value
     *                     upload_valid - passes if upload data is valid
     *                     upload_type - passes if upload data fits filetypes in $arg array
     *                     upload_size - passes if upload data is less than size in $arg string (eg: 1M, 2KiB, 1GB)
     *                     rss2 - passes if data is a valid rss2 feed url
     * @param string $arg  Any extra arguments related to the rule
     * @return void
     */
    public function rule($key, $rule, $arg = NULL);

    /**
     * Adds a custom function to validate the input with key $key.
     *
     * Example:
     * // will call $this->is_existing_account();
     * $validator->callback($this, 'is_existing_account');
     *
     * @param string $key      The key to access the input data value
     * @param array  $function Array($object, string $function_name) which has a
     *                         return type of bool
     * @param array  $args     An array of setup keys to be used as arguments in
     *                         the callback function
     * @return void
     */
    public function callback($key, array $function, array $args);

    /**
     * Runs all of the added rules and callbacks, logging whether or not there
     * are any validation errors.
     *
     * Example:
     * if ($validator->check() === TRUE) {} // All items are valid.
     *
     * @return bool
     */
    public function check();

    /**
     * Returns an array with any errors found.
     *
     * Example:
     * print_r($validator->check()); // return array('keyfoo', 'keybar')
     *
     * @return array
     */
    public function errors();
}
