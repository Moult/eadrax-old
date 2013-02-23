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
     * Auth entity
     * @var Tool\Auth
     */
    private $entity_auth;

    /**
     * Sets up collaborators
     *
     * @return void
     */
    public function __construct(Tool\Auth $entity_auth)
    {
        $this->entity_auth = $entity_auth;
    }

    /**
     * Runs the interaction chain
     *
     * @return void
     */
    public function interact()
    {
        $this->entity_auth->logout();
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
