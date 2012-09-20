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
        if ($this->request->method() === HTTP_Request::POST)
        {
            $factory = $this->factory(NULL, $this->request->post());
            $context = $factory->fetch();
            $context_result = $context->execute();

            if ($context_result['status'] == 'failure'
            AND ! isset($context_result['errors']['authorisation']))
            {
                $view = new View_User_Register;
                $view->errors = $context_result['errors'];
                $view->post_data = $this->request->post();
                $this->response->body($view);
            }
            else
            {
                $this->request->redirect(Route::get('user dashboard')->uri());
            }
        }
        else
        {
            $view = new View_User_Register;
            $this->response->body($view);
        }
    }

    /**
     * Registered users will see their dashboard.
     *
     * TODO
     */
    public function action_dashboard()
    {
    }
}
