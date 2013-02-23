<?php

namespace Eadrax\Core\Usecase\User\Logout;
use Eadrax\Core\Entity;

/**
 * Runs the user logout usecase
 *
 * @package Interactor
 */
class Interactor
{
    /**
     * Auth entity
     * @var Entity\Auth
     */
    private $entity_auth;

    /**
     * Sets up collaborators
     *
     * @return void
     */
    public function __construct(Entity\Auth $entity_auth)
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
