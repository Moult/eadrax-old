<?php
/**
 * Eadrax application/classes/Controller/Project.php
 *
 * @package   Controller
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Controls process flow for project related use cases.
 *
 * @package Controller
 */
class Controller_Project extends Controller_Core
{
    /**
     * For users wanting to add a new project
     * 
     * @return void
     */
    public function action_add()
    {
        $context_result = $this->execute_context(NULL, $this->request->post());

        if ($context_result['status'] === 'success')
            return $this->redirect(Route::get('project view')->uri());
        elseif ($context_result['type'] === 'authorisation')
            return $this->redirect(Route::get('user login')->uri());
        elseif ($context_result['type'] === 'validation')
        {
            if ($this->request->method() === HTTP_Request::POST)
                return $this->display('View_Project_Add', $context_result['data']);
            else
                return $this->display('View_Project_Add');
        }


    }
}
