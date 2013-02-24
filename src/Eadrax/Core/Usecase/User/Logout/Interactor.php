<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Logout;

use Eadrax\Core\Tool;

class Interactor
{
    private $tool_auth;

    public function __construct(Tool\Auth $tool_auth)
    {
        $this->tool_auth = $tool_auth;
    }

    public function interact()
    {
        $this->tool_auth->logout();
    }

    public function execute()
    {
        $this->interact();
        return array(
            'status' => 'success'
        );
    }
}
