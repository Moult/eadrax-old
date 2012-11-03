<?php
/**
 * Eadrax Context/Factory.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context;

/**
 * All context factories must extend this class.
 *
 * @package Context
 */
abstract class Factory
{
    /**
     * Stores input data the factory is given.
     * @var array
     */
    protected $data;

    /**
     * Stores data the factory is given to be used during production
     *
     * @param array $data The data to be used for production
     * @return void
     */
    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    /**
     * Loads available data.
     *
     * @param string $key The key of the data item to retrieve
     * @return mixed
     */
    public function get_data($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key];
        else
            return NULL;
    }
}
