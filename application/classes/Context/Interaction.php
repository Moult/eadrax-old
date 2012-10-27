<?php
/**
 * Eadrax application/classes/Context/Interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Holds interactions that all roles should be capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Context_Interaction
{
    /**
     * Allows the role to speak to another role or object.
     *
     * @return void
     */
    public function link($objects)
    {
        foreach ($objects as $object_name => $object_instance)
        {
            $this->$object_name = $object_instance;
        }
    }
}
