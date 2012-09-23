<?php
/**
 * Eadrax application/classes/controller/core.php
 *
 * @package   Controller
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * All controllers which enact usecases must extend this.
 *
 * Holds generic context loading behavior.
 *
 * @package Controller
 */
abstract class Controller_Core extends Controller
{
    /**
     * Attempts to guess a factory to load either from URI or from param
     *
     * @param string $factory Name of factory to load.
     *
     * @return Context_Core A "Context_Core" subclass object.
     */
    public function factory($factory = NULL, $data = NULL)
    {
        if ($factory === NULL)
            return $this->_factory($this->guess_factory_name_from_uri(), $data);
        else
            return $this->_factory($factory, $data);
    }

    /**
     * Guesses the factory name from the URI.
     *
     * @return string
     */
    private function guess_factory_name_from_uri()
    {
        list($controller, $function) = explode('/', $this->request->uri());
        return 'Context_'.ucfirst($controller).'_'.ucfirst($function).'_Factory';
    }

    /**
     * Loads a factory.
     *
     * @param string $factory Name of the factory class.
     *
     * @return Factory depending on param $factory
     */
    private function _factory($factory, $data)
    {
        if ( ! class_exists($factory))
            throw New HTTP_Exception_404('Factory '.$factory.' not found.');

        return new $factory($data);
    }

    /**
     * Sends data to a factory to produce a context and executes it
     *
     * @return array The results of the context execution
     */
    protected function execute_context($data = NULL)
    {
        $context = $this->factory(NULL, $data)->fetch();
        return $context->execute();
    }

    /**
     * Displays the View_User_Register view
     *
     * @param string $view_name The name of the view to load
     * @param array  $data      The data to send to the view class
     *
     * @return void
     */
    protected function display($view_name, $data = array())
    {
        $view = new $view_name;
        $this->assign_view_data($view, $data);
        $this->response->body($view);
    }

    /**
     * Assigns data to a view
     *
     * @param object &$view The view to assign to
     * @param array  $data  The data to give it
     *
     * @return void
     */
    protected function assign_view_data(&$view, $data)
    {
        foreach ($data as $key => $value)
            $view->$key = $value;
        $view->post_data = $this->request->post();
    }
}
