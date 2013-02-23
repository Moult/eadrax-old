<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Login;

interface Repository
{
    /**
     * Checks whether or not an account exists.
     *
     * @param string $username The username of the account
     * @param string $password The password of the account
     * @return bool
     */
    public function is_existing_account($username, $password);
}
