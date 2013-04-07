<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;

interface Repository
{
    public function is_fan_of($fan, $idol);
    public function add_idol($fan, $idol);
    public function remove_idol($fan, $idol);
    /**
     * @return Eadrax\Core\Data\User
     */
    public function get_username_and_email($user_id);
}
