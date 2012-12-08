<?php
/**
 * Eadrax Context/User/Register/Guest.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\User\Register;
use Eadrax\Core\Data;
use Eadrax\Core\Context;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

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
     * Prove that it is allowed to register an account.
     *
     * @throws Exception\Authorisation if already logged in
     * @return void
     */
    public function authorise_registration()
    {
        if ($this->entity_auth->logged_in())
            throw new Exception\Authorisation('Logged in users cannot register new accounts.');
    }

    /**
     * Makes sure our signup details are valid.
     *
     * @throws Exception\Validation
     * @return void
     */
    public function validate_information()
    {
        $this->setup_validation();

        if ( ! $this->entity_validation->check())
            throw new Exception\Validation($this->entity_validation->errors());
    }

    /**
     * Checks whether or not a username is unique.
     *
     * @param string $username The username to check.
     * @return bool
     */
    public function is_unique_username($username)
    {
        return $this->repository->is_unique_username($username);
    }

    /**
     * Registers the guest as a new user.
     *
     * @return void
     */
    public function register()
    {
        $this->repository->register($this);
    }

    /**
     * Sets up validation rules for checking user details.
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email
        ));

        $this->entity_validation->rule('username', 'not_empty');
        $this->entity_validation->rule('username', 'regex', '/^[a-z_.]++$/iD');
        $this->entity_validation->rule('username', 'min_length', '4');
        $this->entity_validation->rule('username', 'max_length', '15');
        $this->entity_validation->callback('username', array($this, 'is_unique_username'), array('username'));
        $this->entity_validation->rule('password', 'not_empty');
        $this->entity_validation->rule('password', 'min_length', '6');
        $this->entity_validation->rule('email', 'not_empty');
        $this->entity_validation->rule('email', 'email');
    }
}
