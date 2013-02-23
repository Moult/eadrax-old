<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Logout;

use Eadrax\Core\Tool;

class Interactor
{
    /**
     * Auth tool
     * @var Tool\Auth
     */
    private $tool_auth;

    /**
     * Sets up collaborators
     *
     * @return void
     */
    public function __construct(Tool\Auth $tool_auth)
    {
        $this->tool_auth = $tool_auth;
    }

    /**
     * Runs the interaction chain
     *
     * @return void
     */
    public function interact()
    {
        $this->tool_auth->logout();
    }

    /**
     * Executes the interaction, generating a results array
     *
     * @return array
     */
    public function execute()
    {
        $this->interact();
        return array(
            'status' => 'success'
        );
    }
}
