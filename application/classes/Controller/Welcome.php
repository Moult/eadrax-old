<?php
/**
 * Eadrax application/classes/Controller/Welcome.php
 *
 * @package   Controller
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Shows static base pages of the website.
 *
 * @package Controller
 */
class Controller_Welcome extends Controller
{
    /**
     * Homepage of website.
     *
     * @return void
     */
    public function action_index()
    {
        $view = new View_Welcome_Homepage;
        $this->response->body($view);
    }
}
