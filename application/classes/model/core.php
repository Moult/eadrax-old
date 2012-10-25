<?php
/**
 * Eadrax application/classes/model/core.php
 *
 * @package   Model
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * All data model objects must extend this.
 *
 * @package Model
 */
abstract class Model_Core extends Model {
    /**
     * Every data structure should have a unique ID associated with it
     * @var mixed
     */
    public $id;

    /** @ignore */
    public function get_id()
    {
        return $this->id;
    }

    /** @ignore */
    public function set_id($id)
    {
        $this->id = $id;
    }
}
