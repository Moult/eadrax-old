<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Data;

interface Repository
{
    /**
     * Saves a user
     *
     * @param Data_User $data_user The user to save
     * @return void
     */
    public function register(Data\User $data_user);

    /**
     * Checks whether or not a username is unique
     *
     * @param string $username The username to check
     * @return bool
     */
    public function is_unique_username($username);
}
