<?php
/**
 * Eadrax Context/User/Dashboard/Interactor.php
 *
 * @package   Interactor
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\User\Dashboard;
use Eadrax\Core\Exception;

/**
 * Executes the user dashboard view usecase
 *
 * @package Interactor
 */
class Interactor
{
    /**
     * User role
     * @var User
     */
    private $user;

    /**
     * Sets up dependencies
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Runs the interaction chain.
     *
     * @return array
     */
    public function interact()
    {
        return $this->user->authorise_user_dashboard();
    }

    /**
     * Executes the interaction chain, generating a results array
     *
     * @return array
     */
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
