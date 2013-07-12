<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Edit;
use Eadrax\Core\Data;

interface Repository
{
    /**
     * @return string The path of the updated avatar
     * @return null If there is no avatar
     */
    public function update_avatar($user_id, $avatar_name, $avatar_tmp_path, $avatar_mimetype, $avatar_filesize_in_bytes, $avatar_error_code);

    /**
     * @return void
     */
    public function edit_user($user_id, $user_password, $user_email, $user_bio, $user_website, $user_location, $user_avatar_path, $user_dob, $user_gender, $user_show_email, $user_receive_notifications);
}
