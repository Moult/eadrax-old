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
    private $guest;
    private $user_login;

    public function __construct(Guest $guest, User\Login\Interactor $user_login)
    {
        $this->guest = $guest;
        $this->user_login = $user_login;
    }

    public function interact()
    {
        $this->guest->authorise_registration();
        $this->guest->validate_information();
        $this->guest->register();
        $this->user_login->interact();
    }

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
