<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Dashboard\Interactor;
use Eadrax\Core\Usecase\User\Dashboard\User;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Dashboard
{
    private $data_user;
    private $tool_auth;

    public function __construct(Data\User $data_user, Tool\Auth $tool_auth)
    {
        $this->data_user = $data_user;
        $this->tool_auth = $tool_auth;
    }

    public function fetch()
    {
        return new Interactor($this->get_user());
    }

    private function get_user()
    {
        return new User($this->data_user, $this->tool_auth);
    }
}
