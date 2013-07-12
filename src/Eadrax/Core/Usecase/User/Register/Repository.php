<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

interface Repository
{
    /**
     * @return bool
     */
    public function is_unique_username($username);

    /**
     * @return void
     */
    public function register($username, $password, $email);
}
