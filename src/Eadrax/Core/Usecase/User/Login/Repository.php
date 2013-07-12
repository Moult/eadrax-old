<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Login;

interface Repository
{
    /**
     * @return bool
     */
    public function is_existing_account($username, $password);
}
