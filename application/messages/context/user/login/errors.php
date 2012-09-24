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

defined('SYSPATH') OR die('No direct script access.');

return array(
    'username' => array(
        'not_empty'           => 'Your username is required.',
        'is_existing_account' => 'No account with those user details exist.'
    )
);
