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
            'repository_user_login' => $repository_user_login,
            'entity_auth' => $entity_auth,
            'entity_validation' => $entity_validation
        ));
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
            $this->guest->authorise_registration();
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
}
