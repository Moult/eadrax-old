<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Data;

interface Repository
{
    public function register(Data\User $data_user);
    public function is_unique_username($username);
}
