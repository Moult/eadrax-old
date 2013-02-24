<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Dashboard;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class User extends Data\User
{
    public function __construct(Data\User $data_user, Tool\Auth $tool_auth)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }
        $this->tool_auth = $tool_auth;
    }

    public function authorise_dashboard()
    {
        if ($this->tool_auth->logged_in())
            return array(
                'username' => $this->tool_auth->get_user()->username
            );
        else
            throw new Exception\Authorisation('Please login before you can view your dashboard.');
    }
}
