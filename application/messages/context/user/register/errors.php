<?php
/**
 * Eadrax application/messages/context/user/register/errors.php
 *
 * @package   Messages
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') or die('No direct script access.');

return array(
    'username' => array(
        'not_empty'  => 'A username is required.',
        'regex'      => 'Your username contains illegal characters.',
        'min_length' => 'Your username should be more than 4 characters.',
        'max_length' => 'Your username should be less than 15 characters.',
        'is_unique_username' => 'An account with that username has already been registered in our system. Please pick another.'
    ),
    'password' => array(
        'not_empty'  => 'A password is required.',
        'min_length' => 'Your password should be more than 6 characters.'
    ),
    'email' => array(
        'not_empty' => 'An email is required.',
        'email'     => 'Your email is not a real email.'
    )
);
