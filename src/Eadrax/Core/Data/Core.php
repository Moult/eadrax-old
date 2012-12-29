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
     * $data_bar = new Data_Bar($data_foo);
     *
     * @param array $properties The list of properties to preset
     * @return void
     */
    public function __construct($properties = array())
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
        if ( ! empty($property_value) AND method_exists($this, 'set_'.$property_name))
        {
            $this->{'set_'.$property_name}($property_value);
        }
    }

    /**
     * Checks if the data has been set or not.
     *
     * @return bool
     */
    public function exists()
    {
        foreach ($this as $value)
        {
            if ( ! empty($value))
                return TRUE;
        }
        return FALSE;
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
