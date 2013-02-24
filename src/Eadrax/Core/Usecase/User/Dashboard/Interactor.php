<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Dashboard;

use Eadrax\Core\Exception;

class Interactor
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function interact()
    {
        return $this->user->authorise_user_dashboard();
    }

    public function execute()
    {
        try
        {
            $data = $this->interact();
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

        return array(
            'status' => 'success',
            'data' => $data
        );
    }
}
