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
        $context_result = $this->execute_context($this->request->post());

        if ($this->request->method() !== HTTP_Request::POST)
            $this->display('View_User_Register');
        elseif ($context_result['status'] === 'success')
            $this->request->redirect(Route::get('user dashboard')->uri());
        elseif ($context_result['type'] === 'authorisation')
            $this->request->redirect(Route::get('user dashboard')->uri());
        elseif ($context_result['type'] === 'validation')
            $this->display('View_User_Register', $context_result['data']);
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
}
