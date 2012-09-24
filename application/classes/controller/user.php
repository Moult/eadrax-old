<?php
/**
 * Eadrax application/classes/controller/user.php
 *
 * @package   Controller
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Controls process flow for user related use cases.
 *
 * @package Controller
 */
class Controller_User extends Controller_Core
{
    /**
     * For people wanting to register an account
     * 
     * @return void
     */
    public function action_register()
    {
        $context_result = $this->execute_context(NULL, $this->request->post());

        if ($context_result['status'] === 'success'
            OR $context_result['type'] === 'authorisation')
            return $this->request->redirect(Route::get('user dashboard')->uri());
        elseif ($context_result['type'] === 'validation')
        {
            if ($this->request->method() === HTTP_Request::POST)
                return $this->display('View_User_Register', $context_result['data']);
            else
                return $this->display('View_User_Register');
        }


    }

    /**
     * Registered users will see their dashboard.
     *
     * TODO
     */
    public function action_dashboard()
    {
        $this->response->body(new View_User_Dashboard);
    }

    /**
     * For users to be able to login into the system.
     *
     * @return void
     */
    public function action_login()
    {
        $context_result = $this->execute_context(NULL, $this->request->post());

        if ($context_result['status'] === 'success'
            OR $context_result['type'] === 'authorisation')
            return $this->request->redirect(Route::get('user dashboard')->uri());
        elseif ($context_result['type'] === 'validation')
        {
            if ($this->request->method() === HTTP_Request::POST)
                return $this->display('View_User_Login', $context_result['data']);
            else
                return $this->display('View_User_Login');
        }
    }
}
