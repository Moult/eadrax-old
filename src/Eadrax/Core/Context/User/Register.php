<?php
/**
 * Eadrax Context/User/Register.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\User;
use Eadrax\Core\Context;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Context\User\Register\Guest;
use Eadrax\Core\Context\User\Register\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Entity;

/**
 * Enacts the usecase for user registration.
 *
 * @package Context
 */
class Register extends Core
{
    /**
     * Guest role
     * @var Guest
     */
    public $guest;

    private $repository_user_login;
    private $entity_auth;
    private $entity_validation;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\User                     $data_user             User data object
     * @param Repository                    $repository            Repository for this context
     * @param Context\User\Login\Repository $repository_user_login Repository for user login context
     * @param Entity\Auth                   $entity_auth           Authentication system
     * @param Entity\Validation             $entity_validation     Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Context\User\Login\Repository $repository_user_login, Entity\Auth $entity_auth, Entity\Validation $entity_validation)
    {
        $this->guest = new Guest($data_user);
        $this->guest->link(array(
            'repository' => $repository,
            'entity_auth' => $entity_auth,
            'entity_validation' => $entity_validation
        ));

        $this->repository_user_login = $repository_user_login;
        $this->entity_auth = $entity_auth;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
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
                'type'   => 'authorisation',
                'data'   => array(
                    'errors' => array($e->getMessage())
                )
            );
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'validation',
                'data'   => array(
                    'errors' => $e->as_array()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }

    /**
     * Runs the interaction chain
     *
     * @throws Exception\Authorisation
     * @throws Exception\Validation
     * @return void
     */
    public function interact()
    {
        $this->guest->authorise_registration();
        $this->guest->validate_information();
        $this->guest->register();
        $this->context_user_login()->interact();
    }

    /**
     * Creates a login user context.
     *
     * @return Context\User\Login
     */
    private function context_user_login()
    {
        return new Context\User\Login($this->guest, $this->repository_user_login, $this->entity_auth, $this->entity_validation);
    }
}
