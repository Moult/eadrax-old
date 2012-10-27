<?php
/**
 * Eadrax application/classes/View/Layout.php
 *
 * @package   View
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Sets up partials, essentially a core file for KOstache.
 *
 * @package View
 */
abstract class View_Layout extends Kostache_Layout
{
    /**
     * Mustache partials like headers, footers, sidebars
     * @var array
     */
    protected $_partials = array(
        'header' => 'partial/header',
        'footer' => 'partial/footer'
    );

    /**
     * Base url of website.
     * @var string
     */
    public $baseurl;

    /**
     * Sets up useful sitewide variables.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseurl = URL::base();
    }

    /**
     * Returns core partials.
     *
     * @return array
     */
    public function get_partials()
    {
        return $this->_partials;
    }
}
