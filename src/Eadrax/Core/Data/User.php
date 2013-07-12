<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Data;

class User
{
    public $id;
    public $username;
    public $password;
    public $password_verify;
    public $email;
    public $bio;
    public $website;
    public $location;
    /**
     * @var File
     */
    public $avatar;
    public $dob;
    public $gender;
    public $show_email;
    public $receive_notifications;
}
