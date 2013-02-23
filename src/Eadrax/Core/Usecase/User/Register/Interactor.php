<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Usecase\User;
use Eadrax\Core\Exception;

class Interactor
{
    /**
     * Guest role
     * @var Guest
     */
    private $guest;

    /**
     * User login interactor
     * @var User\Login\Interactor
     */
    private $user_login;

    /**
     * Sets up collaborators
     *
     * @return void
     */
    public function __construct(Guest $guest, User\Login\Interactor $user_login)
    {
        $this->guest = $guest;
        $this->user_login = $user_login;
    }

    /**
     * Runs the interaction chain
     *
     * @return void
     */
    public function interact()
    {
        $this->guest->authorise_registration();
        $this->guest->validate_information();
        $this->guest->register();
        $this->user_login->interact();
    }

    /**
     * Runs the interaction, generating a result array
     *
     * @return array
     */
    public function execute()
    {
        try
        {
            $this->interact();
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'authorisation',
                'data' => array(
                    'errors' => array($e->getMessage())
                )
            );
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'validation',
                'data' => array(
                    'errors' => $e->get_errors()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
