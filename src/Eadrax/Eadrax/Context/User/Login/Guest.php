<?php
/**
 * Eadrax Context/User/Login/Guest.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Login;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Entity;
use Eadrax\Eadrax\Exception;

/**
 * Allows data_user to be cast as a guest role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Guest extends Data\User
{
    use Context\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\User $data_user Data object to copy
     * @return void
     */
    public function __construct(Data\User $data_user = NULL)
    {
        parent::__construct(get_object_vars($data_user));
    }

    /**
     * Prove that it is allowed to login an account.
     *
     * @throws Exception\Authorisation if already logged in
     * @return void
     */
    public function authorise_login()
    {
        if ($this->entity_auth->logged_in())
            throw new Exception\Authorisation('Logged in users don\'t need to login again.');
        else
            return $this->validate_information();
    }

    /**
     * Makes sure our signup details are valid.
     *
     * @throws Exception\Validation
     * @return void
     */
    public function validate_information()
    {
        $this->entity_validation->setup(array(
                'username' => $this->username
            ));
        $this->entity_validation->rule('username', 'not_empty');
        $this->entity_validation->callback('username', array($this, 'is_existing_account'), array($this->username, $this->password));

        if ($this->entity_validation->check())
            return $this->login();
        else
            throw new Exception\Validation($this->entity_validation->errors());
    }

    /**
     * Checks whether or not a username is unique.
     *
     * @param string $username The username to check.
     * @param string $password The password to check.
     * @return bool
     */
    public function is_existing_account($username, $password)
    {
        return $this->repository->is_existing_account($username, $password);
    }

    /**
     * Logs the guest into the system.
     *
     * @return void
     */
    public function login()
    {
        return $this->entity_auth->login($this->username, $this->password);
    }
}
