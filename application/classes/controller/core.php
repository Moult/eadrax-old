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
    public function factory($factory = NULL)
    {
        if ($factory !== NULL)
        {
            return $this->_factory($factory);
        }
        else
        {
            // Create a factory name from the URI.
            list($controller, $function) = explode('/', $this->request->uri());
            $factory = 'Context_'.ucfirst($controller).'_'.ucfirst($function).'_Factory';
            return $this->_factory($factory);
        }
    }

    /**
     * Loads a factory.
     *
     * @param string $factory Name of the factory class.
     *
     * @return Factory depending on param $factory
     */
    private function _factory($factory)
    {
        if ( ! class_exists($factory))
        {
            throw New HTTP_Exception_404('Factory '.$factory.' not found.');
        }

        return new $factory;
    }
}
