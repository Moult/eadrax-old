<?php
/**
 * Eadrax Context/User/Login.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\User;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Context\User\Login\Guest;
use Eadrax\Core\Context\User\Login\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Enacts the usecase for user login.
 *
 * @package Context
 */
class Login extends Core
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
     * @param Data\User         $data_user         User data object
     * @param Repository        $repository        The repository
     * @param Entity\Auth       $entity_auth       Authentication system
     * @param Entity\Validation $entity_validation Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Entity\Auth $entity_auth, Entity\Validation $entity_validation)
    {
        $this->guest = new Guest($data_user);
        $this->guest->link(array(
            'repository' => $repository,
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
            $this->guest->authorise_login();
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
