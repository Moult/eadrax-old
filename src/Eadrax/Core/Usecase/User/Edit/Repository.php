<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Edit;
use Eadrax\Core\Data;

interface Repository
{
    public function edit_user(Data\User $user);
}
