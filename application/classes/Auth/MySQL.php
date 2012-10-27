<?php
/**
 * Eadrax application/classes/Auth/MySQL.php
 *
 * @package   Auth
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * This is a driver for the KO3 auth module.
 *
 * This differs from the ORM auth driver in that:
 *  1. It does not use ORM. ORM is bloat.
 *  2. It does not understand roles.
 *  3. It does not store the entire user model in the session, instead it only 
 *     stores the id and username.
 *
 * @package Auth
 */
class Auth_MySQL extends Auth
{
    /**
     * Do username / password check
     *
     * @param string $username The username to check
     * @param string $password The password to check
     * @param string $remember Enable autologin
     * @return bool
     */
    protected function _login($username, $password, $remember)
    {
        // Load the user
        $query = DB::select('id', 'username', 'password')->from('users')->where('username', '=', $username)->limit(1)->as_object()->execute();
        $user = $query->current();

        if (is_object($user))
        {
            if (is_string($password))
            {
                // Create a hashed password
                $password = $this->hash($password);
            }

            // If the passwords match, perform a login
            if ($user->password === $password)
            {
                if ($remember === TRUE)
                {
                    // Token data
                    $data = array(
                        'user_id'    => $user->id,
                        'expires'    => time() + $this->_config['lifetime'],
                        'user_agent' => sha1(Request::$user_agent),
                    );

                    // Create a new autologin token
                    $query = DB::insert('user_token', array(
                        'uid',
                        'expires',
                        'user_agent'
                    ))->values(array(
                        $data['uid'],
                        $data['expires'],
                        $data['user_agent']
                    ))->execute();
                    $insert_id = $query[0];

                    $query = DB::select('token')->from('user_tokens')->where('id', '=', $insert_id)->limit(1)->as_object()->execute();
                    $token = $query->current();

                    // Set the autologin cookie
                    Cookie::set('authautologin', $token->token, $this->_config['lifetime']);
                }

                // Finish the login
                unset($user->password);
                return $this->complete_login($user);
            }
        }

		// Login failed
		return FALSE;

    }

    /**
     * Return the password for the username.
     *
	 * @param string $username The username to check
     * @return string
     */
    public function password($username)
    {
        // Load the user
        $query = DB::select('password')->from('users')->where('username', '=', $username)->limit(1)->execute();
        return $query->get('password', FALSE);
    }
 
    /**
     * Check to see if the logged in user has the given password.
     *
     * This always returns FALSE. This driver does not store password data in 
     * the session. Only implemented to satisfy the driver interface.
     *
	 * @param string $password The password to check
     * @return bool
     */
    public function check_password($password)
    {
        return FALSE;
    }
 
    /**
     * Check to see if the user is logged in
     *
     * @param string $role Deprecated in our auth driver - implemented purely to 
     *                     satisfy the auth driver interface
     * @return bool
     */
    public function logged_in($role = NULL)
    {
		// Get the user from the session
		$user = $this->get_user();

		if ( ! $user)
			return FALSE;

        return TRUE;
    }
 
    /**
     * Get the logged in user, or return the $default if a user is not found
     *
	 * @param mixed $default To return in case user isn't logged in
     * @return mixed
     */
    public function get_user($default = NULL)
    {
        $user = parent::get_user($default);

		if ($user === $default)
		{
			// check for "remembered" login
			if (($user = $this->auto_login()) === FALSE)
				return $default;
		}

		return $user;
    }

    /**
     * Logs a user in, based on the authautologin cookie.
     *
     * @return mixed
     */
	public function auto_login()
	{
		if ($token = Cookie::get('authautologin'))
		{
			// Load the token and user
            $query = DB::select('token', 'expires', 'user_agent')->from('user_tokens')->where('token', '=', $token)->limit(1)->as_object()->execute();
            $token = $query->current();

            if ($token->user_agent === sha1(Request::$user_agent))
            {

				// Create a new autologin token
                $query = DB::update('user_token')->set(array(
                    'expires' => $token->expires - time()
                ))->where('token', '=', $token->token)->execute();
                $insert_id = $query[0];

                $query = DB::select('token', 'expires', 'user_agent')->from('user_tokens')->where('id', '=', $insert_id)->limit(1)->as_object()->execute();
                $token = $query->current();

                // Set the new token
                Cookie::set('authautologin', $token->token, $token->expires - time());

                // Complete the login with the found data
                $query = DB::select('id', 'username')->from('users')->where('id', '=', $token->uid)->limit(1)->as_object()->execute();
                $user = $query->current();
                $this->complete_login($user);

                // Automatic login was successful
                return $user;
            }

            // Token is invalid
            DB::delete('user_token')->where('token', '=', $token->token)->execute();
		}

		return FALSE;
	}
}
