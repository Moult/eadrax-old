<?php
/**
 * Eadrax Data/Core.php
 *
 * @package   Data
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Data;

/**
 * All data objects must extend this.
 *
 * @package Data
 */
abstract class Core {
    /**
     * Every data structure should have a unique ID associated with it
     * @var mixed
     */
    public $id;

    /**
     * Allows you to set properties whilst instantiating the object.
     *
     * $data_foo = new Data_Foo(array('foo' => 'bar'));
     *
     * @param array $properties The list of properties to preset
     * @return void
     */
    public function __construct(Array $properties = array())
    {
        foreach ($properties as $property_name => $property_value) {
            $this->set_property($property_name, $property_value);
        }
    }

    /**
     * Sets a property of the data.
     *
     * @param string $property_name  The name of the property to set
     * @param string $property_value The value to set
     * @return void
     */
    private function set_property($property_name, $property_value)
    {
        if ( ! empty($property_value))
        {
            $this->{'set_'.$property_name}($property_value);
        }
    }

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
